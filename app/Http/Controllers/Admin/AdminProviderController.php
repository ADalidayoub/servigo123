<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;

class AdminProviderController extends Controller
{
    // ==================== Registration Requests ====================

    public function pendingRegistrations()
    {
        $pending = Provider::where('status', 'pending')
            ->with(['user', 'mainService'])
            ->get()
            ->map(function ($provider) {
                return [
                    'id' => $provider->id,
                    'user_id' => $provider->user_id,
                    'name' => $provider->user->name,
                    'main_service' => [
                        'name_ar' => $provider->mainService?->name_ar,
                        'name_en' => $provider->mainService?->name_en,
                    ],
                    'created_at' => $provider->created_at->toDateString(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'count' => $pending->count(),
                'requests' => $pending,
            ],
        ]);
    }

    public function registrationDetails($providerId)
    {
        $provider = Provider::with(['user', 'mainService'])->find($providerId);

        if (!$provider) {
            return response()->json(['success' => false, 'message' => 'provider_not_found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $provider->id,
                'user_id' => $provider->user_id,
                'name' => $provider->user->name,
                'phone' => $provider->user->phone,
                'email' => $provider->user->email,
                'location_name' => $provider->location_name,
                'latitude' => $provider->latitude,
                'longitude' => $provider->longitude,
                'main_service' => [
                    'name_ar' => $provider->mainService?->name_ar,
                    'name_en' => $provider->mainService?->name_en,
                ],
                'work_type' => $provider->work_type,
                'id_photo_front' => $provider->id_photo_front,
                'id_photo_back' => $provider->id_photo_back,
                'status' => $provider->status,
                'created_at' => $provider->created_at->toDateString(),
            ],
        ]);
    }

    public function approveRegistration($providerId)
    {
        $provider = Provider::find($providerId);

        if (!$provider) {
            return response()->json(['success' => false, 'message' => 'provider_not_found'], 404);
        }

        if ($provider->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'request_already_processed'], 422);
        }

        $provider->update(['status' => 'approved']);

        return response()->json([
            'success' => true,
            'message' => 'Provider approved successfully',
        ]);
    }

    public function rejectRegistration(Request $request, $providerId)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $provider = Provider::find($providerId);

        if (!$provider) {
            return response()->json(['success' => false, 'message' => 'provider_not_found'], 404);
        }

        if ($provider->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'request_already_processed'], 422);
        }

        $provider->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Provider rejected successfully',
        ]);
    }

    // ==================== Provider Management ====================

    public function index(Request $request)
    {
        $query = Provider::where('status', 'approved')
            ->with(['user', 'mainService', 'subService']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('filter')) {
            switch ($request->filter) {
                case 'active':
                    $query->whereHas('user', function ($q) {
                        $q->where('is_banned', false);
                    })->where('profile_completed', true);
                    break;
                case 'banned':
                    $query->whereHas('user', function ($q) {
                        $q->where('is_banned', true);
                    });
                    break;
                case 'incomplete':
                    $query->where('profile_completed', false);
                    break;
            }
        }

        $providers = $query->get()->map(function ($provider) {
            return [
                'id' => $provider->id,
                'user_id' => $provider->user_id,
                'photo' => $provider->id_photo_front,
                'name' => $provider->user->name,
                'main_service' => [
                    'name_ar' => $provider->mainService?->name_ar,
                    'name_en' => $provider->mainService?->name_en,
                ],
                'sub_service' => [
                    'name_ar' => $provider->subService?->name_ar,
                    'name_en' => $provider->subService?->name_en,
                ],
                'work_type' => $provider->work_type,
                'is_banned' => $provider->user->is_banned,
                'profile_completed' => $provider->profile_completed,
                'joined_at' => $provider->user->created_at->toDateString(),
            ];
        });

        $totalApproved = Provider::where('status', 'approved')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_approved' => $totalApproved,
                'providers' => $providers,
            ],
        ]);
    }

    public function show($providerId)
    {
        $provider = Provider::with(['user', 'mainService', 'subService', 'certificates', 'portfolio', 'offDays'])
            ->find($providerId);

        if (!$provider) {
            return response()->json(['success' => false, 'message' => 'provider_not_found'], 404);
        }

        $ratings = Rating::where('provider_id', $provider->id)
            ->with('user:id,name')
            ->latest()
            ->get()
            ->map(function ($r) {
                return [
                    'id' => $r->id,
                    'user_name' => $r->user?->name ?? 'مستخدم محذوف',
                    'rating' => $r->rating,
                    'review' => $r->review,
                    'created_at' => $r->created_at->toDateString(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $provider->id,
                'user_id' => $provider->user_id,
                'name' => $provider->user->name,
                'phone' => $provider->user->phone,
                'email' => $provider->user->email,
                'photo' => $provider->id_photo_front,
                'location_name' => $provider->location_name,
                'latitude' => $provider->latitude,
                'longitude' => $provider->longitude,
                'location_description' => $provider->location_description,
                'main_service' => [
                    'id' => $provider->mainService?->id,
                    'name_ar' => $provider->mainService?->name_ar,
                    'name_en' => $provider->mainService?->name_en,
                ],
                'sub_service' => [
                    'id' => $provider->subService?->id,
                    'name_ar' => $provider->subService?->name_ar,
                    'name_en' => $provider->subService?->name_en,
                ],
                'work_type' => $provider->work_type,
                'min_price' => $provider->min_price,
                'max_price' => $provider->max_price,
                'currency' => $provider->currency,
                'about_me' => $provider->about_me,
                'work_start_time' => $provider->work_start_time,
                'work_end_time' => $provider->work_end_time,
                'overnight' => $provider->overnight,
                'off_days' => $provider->off_days_array,
                'id_photo_front' => $provider->id_photo_front,
                'id_photo_back' => $provider->id_photo_back,
                'profile_completed' => $provider->profile_completed,
                'is_banned' => $provider->user->is_banned,
                'avg_rating' => $provider->avg_rating,
                'ratings_count' => $provider->ratings_count,
                'certificates' => $provider->certificates,
                'portfolio' => $provider->portfolio,
                'ratings' => $ratings,
                'joined_at' => $provider->user->created_at->toDateString(),
            ],
        ]);
    }

    public function ban($providerId)
    {
        $provider = Provider::with('user')->find($providerId);

        if (!$provider) {
            return response()->json(['success' => false, 'message' => 'provider_not_found'], 404);
        }

        $provider->user->update(['is_banned' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Provider banned successfully',
        ]);
    }

    public function unban($providerId)
    {
        $provider = Provider::with('user')->find($providerId);

        if (!$provider) {
            return response()->json(['success' => false, 'message' => 'provider_not_found'], 404);
        }

        $provider->user->update(['is_banned' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Provider unbanned successfully',
        ]);
    }

    public function delete($providerId)
    {
        $provider = Provider::with('user')->find($providerId);

        if (!$provider) {
            return response()->json(['success' => false, 'message' => 'provider_not_found'], 404);
        }

        $provider->user->tokens()->delete();
        $provider->delete();
        $provider->user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Provider deleted successfully. Data will be permanently removed after 30 days.',
        ]);
    }

    public function update(Request $request, $providerId)
    {
        $provider = Provider::with('user')->find($providerId);

        if (!$provider) {
            return response()->json(['success' => false, 'message' => 'provider_not_found'], 404);
        }

        $userFields = $request->only(['name', 'phone', 'email']);
        if (!empty($userFields)) {
            if (isset($userFields['email'])) {
                $exists = User::where('email', $userFields['email'])
                    ->where('id', '!=', $provider->user_id)->exists();
                if ($exists) {
                    return response()->json(['success' => false, 'message' => 'email_already_taken'], 422);
                }
            }
            $provider->user->update($userFields);
        }

        $providerFields = $request->only([
            'location_name', 'latitude', 'longitude', 'location_description',
            'work_type', 'main_service_id', 'sub_service_id',
            'currency', 'min_price', 'max_price',
            'work_start_time', 'work_end_time', 'overnight', 'about_me'
        ]);
        if (!empty($providerFields)) {
            $provider->update($providerFields);
        }

        return response()->json([
            'success' => true,
            'message' => 'Provider updated successfully',
        ]);
    }
}
