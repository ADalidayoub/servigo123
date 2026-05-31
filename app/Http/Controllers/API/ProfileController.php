<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CommentReport;
use App\Models\Complaint;
use App\Models\Favourite;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    private function getUserPhoto($user)
    {
        return $user && $user->photo && Storage::disk('public')->exists($user->photo)
            ? asset('storage/' . $user->photo)
            : null;
    }

    public function show($userId)
    {
        $currentUser = auth()->user();

        if (!$currentUser) {
            return response()->json(['success' => false, 'message' => 'unauthorized'], 401);
        }

        $targetUser = User::find($userId);

        if (!$targetUser) {
            return response()->json(['success' => false, 'message' => 'user_not_found'], 404);
        }

        if ($targetUser->role === 'provider') {
            $provider = $targetUser->provider;

            if (!$provider) {
                return response()->json(['success' => false, 'message' => 'provider_not_found'], 404);
            }

            $avgRating = Rating::where('provider_id', $targetUser->id)->avg('rating') ?? 0;
            $ratingsCount = Rating::where('provider_id', $targetUser->id)->count();

            $ratings = Rating::where('provider_id', $targetUser->id)
                ->with('user:id,name,photo')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($rating) {
                    return [
                        'id' => $rating->id,
                        'customer_name' => $rating->user->name,
                        'customer_photo' => $this->getUserPhoto($rating->user),
                        'rating' => $rating->rating,
                        'review' => $rating->review,
                        'created_at' => $rating->created_at->toDateTimeString(),
                    ];
                });

            $portfolio = $provider->portfolio->map(function ($item) {
                return [
                    'id' => $item->id,
                    'file_path' => asset('storage/' . $item->file_path),
                    'file_type' => $item->file_type,
                    'description' => $item->description,
                ];
            });

            $certificates = $provider->certificates->map(function ($cert) {
                return [
                    'id' => $cert->id,
                    'file_path' => asset('storage/' . $cert->file_path),
                ];
            });

            $isFavourite = false;
            if ($currentUser->role === 'user') {
                $isFavourite = Favourite::where('user_id', $currentUser->id)
                    ->where('provider_id', $targetUser->id)
                    ->exists();
            }

            return response()->json([
                'success' => true,
                'message' => 'success',
                'data' => [
                    'id' => $targetUser->id,
                    'name' => $targetUser->name,
                    'phone' => $targetUser->phone,
                    'photo' => $this->getUserPhoto($targetUser),
                    'role' => 'provider',
                    'avg_rating' => round($avgRating, 1),
                    'ratings_count' => $ratingsCount,
                    'ratings' => $ratings,
                    'portfolio' => $portfolio,
                    'certificates' => $certificates,
                    'about_me' => $provider->about_me,
                    'main_service' => [
                        'id' => $provider->main_service_id,
                        'name_ar' => $provider->mainService?->name_ar,
                        'name_en' => $provider->mainService?->name_en,
                    ],
                    'sub_service' => [
                        'id' => $provider->sub_service_id,
                        'name_ar' => $provider->subService?->name_ar,
                        'name_en' => $provider->subService?->name_en,
                    ],
                    'work_type' => $provider->work_type,
                    'location_name' => $provider->location_name,
                    'location_description' => $provider->location_description,
                    'currency' => $provider->currency,
                    'min_price' => $provider->min_price,
                    'max_price' => $provider->max_price,
                    'work_start_time' => $provider->work_start_time,
                    'work_end_time' => $provider->work_end_time,
                    'overnight' => $provider->overnight,
                    'off_days' => $provider->off_days_array,
                    'is_available' => $provider->is_available,
                    'is_favourite' => $isFavourite,
                ]
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'success',
                'data' => [
                    'id' => $targetUser->id,
                    'name' => $targetUser->name,
                    'phone' => $targetUser->phone,
                    'photo' => $this->getUserPhoto($targetUser),
                    'role' => 'user',
                ]
            ]);
        }
    }

    public function rateProvider(Request $request, $providerId)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'unauthorized'], 401);
        }

        if ($user->role !== 'user') {
            return response()->json(['success' => false, 'message' => 'only_customers_can_rate'], 403);
        }

        $providerUser = User::where('id', $providerId)->where('role', 'provider')->first();

        if (!$providerUser) {
            return response()->json(['success' => false, 'message' => 'provider_not_found'], 404);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500',
        ]);

        $existingRating = Rating::where('provider_id', $providerId)
            ->where('user_id', $user->id)
            ->first();

        if ($existingRating) {
            return response()->json([
                'success' => false,
                'message' => 'you_already_rated_this_provider',
            ], 409);
        }

        $rating = Rating::create([
            'provider_id' => $providerId,
            'user_id' => $user->id,
            'rating' => $validated['rating'],
            'review' => $validated['review'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'rating_added_successfully',
            'data' => [
                'id' => $rating->id,
                'rating' => $rating->rating,
                'review' => $rating->review,
                'created_at' => $rating->created_at->toDateTimeString(),
            ]
        ], 201);
    }

    public function complaintProvider(Request $request, $providerId)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'unauthorized'], 401);
        }

        if ($user->role !== 'user') {
            return response()->json(['success' => false, 'message' => 'only_customers_can_submit_complaints'], 403);
        }

        $providerUser = User::where('id', $providerId)->where('role', 'provider')->first();

        if (!$providerUser) {
            return response()->json(['success' => false, 'message' => 'provider_not_found'], 404);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $complaint = Complaint::create([
            'user_id' => $user->id,
            'provider_id' => $providerId,
            'message' => $validated['message'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'complaint_submitted_successfully',
            'data' => [
                'id' => $complaint->id,
                'provider_id' => $providerId,
                'message' => $complaint->message,
                'created_at' => $complaint->created_at->toDateTimeString(),
            ]
        ], 201);
    }

    public function toggleFavourite($providerId)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'unauthorized'], 401);
        }

        if ($user->role !== 'user') {
            return response()->json(['success' => false, 'message' => 'only_customers_can_add_favourites'], 403);
        }

        $providerUser = User::where('id', $providerId)->where('role', 'provider')->first();

        if (!$providerUser) {
            return response()->json(['success' => false, 'message' => 'provider_not_found'], 404);
        }

        $existing = Favourite::where('user_id', $user->id)
            ->where('provider_id', $providerId)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json([
                'success' => true,
                'message' => 'provider_removed_from_favourites',
                'data' => ['is_favourite' => false]
            ]);
        } else {
            Favourite::create([
                'user_id' => $user->id,
                'provider_id' => $providerId,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'provider_added_to_favourites',
                'data' => ['is_favourite' => true]
            ], 201);
        }
    }

    public function getProviderLocation($providerId)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'unauthorized'], 401);
        }

        $providerUser = User::where('id', $providerId)->where('role', 'provider')->first();

        if (!$providerUser) {
            return response()->json(['success' => false, 'message' => 'provider_not_found'], 404);
        }

        $provider = $providerUser->provider;

        if (!$provider) {
            return response()->json(['success' => false, 'message' => 'provider_profile_not_found'], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => [
                'provider_id' => $providerId,
                'name' => $providerUser->name,
                'latitude' => $provider->latitude,
                'longitude' => $provider->longitude,
                'location_name' => $provider->location_name,
            ]
        ]);
    }

    public function reportComment(Request $request, $ratingId)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'unauthorized'], 401);
        }

        if ($user->role !== 'provider') {
            return response()->json(['success' => false, 'message' => 'only_providers_can_report_comments'], 403);
        }

        $rating = Rating::find($ratingId);

        if (!$rating) {
            return response()->json(['success' => false, 'message' => 'rating_not_found'], 404);
        }

        if ($rating->provider_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'you_can_only_report_your_own_ratings'], 403);
        }

        $existing = CommentReport::where('rating_id', $ratingId)
            ->where('provider_id', $user->id)
            ->first();

        if ($existing) {
            return response()->json(['success' => false, 'message' => 'already_reported'], 409);
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $report = CommentReport::create([
            'rating_id' => $ratingId,
            'provider_id' => $user->id,
            'reason' => $validated['reason'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'comment_reported_successfully',
            'data' => [
                'id' => $report->id,
                'rating_id' => (int) $ratingId,
                'reason' => $report->reason,
            ]
        ], 201);
    }
}
