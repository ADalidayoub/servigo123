<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\Certificate;
use App\Models\Portfolio;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProviderProfileController extends Controller
{
    private function getUserPhoto($user)
    {
        return $user && $user->photo && Storage::disk('public')->exists($user->photo)
            ? asset('storage/' . $user->photo)
            : null;
    }

    public function show()
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'provider') {
            return response()->json(['success' => false, 'message' => 'unauthorized'], 401);
        }

        $provider = $user->provider;

        if (!$provider) {
            return response()->json(['success' => false, 'message' => 'provider_not_found'], 404);
        }

        $avgRating = Rating::where('provider_id', $user->id)->avg('rating') ?? 0;

        $ratings = Rating::where('provider_id', $user->id)
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

        $certificates = $provider->certificates->map(function ($cert) {
            return [
                'id' => $cert->id,
                'file_path' => asset('storage/' . $cert->file_path),
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

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'email' => $user->email,
                    'photo' => $this->getUserPhoto($user),
                ],
                'provider' => [
                    'id' => $provider->id,
                    'location_name' => $provider->location_name,
                    'latitude' => $provider->latitude,
                    'longitude' => $provider->longitude,
                    'location_description' => $provider->location_description,
                    'work_type' => $provider->work_type,
                    'main_service_id' => $provider->main_service_id,
                    'main_service_name' => $provider->mainService?->name_ar,
                    'sub_service_id' => $provider->sub_service_id,
                    'sub_service_name' => $provider->subService?->name_ar,
                    'currency' => $provider->currency,
                    'min_price' => $provider->min_price,
                    'max_price' => $provider->max_price,
                    'work_start_time' => $provider->work_start_time,
                    'work_end_time' => $provider->work_end_time,
                    'overnight' => $provider->overnight,
                    'about_me' => $provider->about_me,
                    'off_days' => $provider->off_days_array,
                    'is_available' => $provider->is_available,
                    'status' => $provider->status,
                    'profile_completed' => $provider->profile_completed,
                ],
                'avg_rating' => round($avgRating, 1),
                'ratings' => $ratings,
                'certificates' => $certificates,
                'portfolio' => $portfolio,
            ]
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'provider') {
            return response()->json(['success' => false, 'message' => 'unauthorized'], 401);
        }

        $provider = $user->provider;

        if (!$provider) {
            return response()->json(['success' => false, 'message' => 'provider_not_found'], 404);
        }

        $validated = $request->validate([
            'name' => 'nullable|string',
            'phone' => 'nullable|string',
            'location_name' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'location_description' => 'nullable|string',
            'work_type' => 'nullable|in:fixed,mobile,both',
            'currency' => 'nullable|string|size:3',
            'min_price' => 'nullable|numeric|min:0.01',
            'max_price' => 'nullable|numeric|gte:min_price',
            'work_start_time' => 'nullable|date_format:H:i',
            'work_end_time' => 'nullable|date_format:H:i',
            'overnight' => 'nullable|boolean',
            'about_me' => 'nullable|string',
            'is_available' => 'nullable|boolean',
            'off_days' => 'nullable|array',
'off_days.*' => 'in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
        ]);

        DB::beginTransaction();

        try {
            if (isset($validated['name'])) {
                $user->name = $validated['name'];
            }
            if (isset($validated['phone'])) {
                $user->phone = $validated['phone'];
            }
            $user->save();

            $providerData = array_diff_key($validated, array_flip(['name', 'phone']));
            if (!empty($providerData)) {
                $provider->update($providerData);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'profile_updated_successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'update_failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateAvatar(Request $request)
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'provider') {
            return response()->json(['success' => false, 'message' => 'unauthorized'], 401);
        }

        $validated = $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'remove_photo' => 'nullable|boolean',
        ]);

        if (isset($validated['remove_photo']) && $validated['remove_photo']) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = null;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'photo_removed_successfully',
            ]);
        }

        if ($request->hasFile('photo')) {
            $photoFile = $request->file('photo');
            if ($photoFile && $photoFile->isValid()) {
                $photoPath = 'users/photos/' . uniqid() . '_' . $photoFile->getClientOriginalName();
                Storage::disk('public')->put($photoPath, file_get_contents($photoFile->getPathname()));

                if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }

                $user->photo = $photoPath;
                $user->save();

                return response()->json([
                    'success' => true,
                    'message' => 'photo_updated_successfully',
                    'data' => [
                        'photo_url' => asset('storage/' . $photoPath),
                    ]
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'no_valid_photo_provided',
        ], 422);
    }

    public function manageCertificates(Request $request)
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'provider') {
            return response()->json(['success' => false, 'message' => 'unauthorized'], 401);
        }

        $provider = $user->provider;

        if (!$provider) {
            return response()->json(['success' => false, 'message' => 'provider_not_found'], 404);
        }

        $validated = $request->validate([
            'certificates' => 'nullable|array',
            'certificates.*' => 'image|mimes:jpeg,jpg,png|max:5120',
            'remove_certificate_ids' => 'nullable|array',
            'remove_certificate_ids.*' => 'integer|exists:certificates,id',
        ]);

        if (isset($validated['remove_certificate_ids'])) {
            foreach ($validated['remove_certificate_ids'] as $certId) {
                $cert = Certificate::where('id', $certId)
                    ->where('provider_id', $provider->id)
                    ->first();

                if ($cert) {
                    if (Storage::disk('public')->exists($cert->file_path)) {
                        Storage::disk('public')->delete($cert->file_path);
                    }
                    $cert->delete();
                }
            }
        }

        if ($request->hasFile('certificates')) {
            foreach ($request->file('certificates') as $certFile) {
                if ($certFile && $certFile->isValid()) {
                    $path = 'providers/certificates/' . uniqid() . '_' . $certFile->getClientOriginalName();
                    Storage::disk('public')->put($path, file_get_contents($certFile->getPathname()));
                    Certificate::create([
                        'provider_id' => $provider->id,
                        'file_path' => $path,
                    ]);
                }
            }
        }

        $certificates = $provider->certificates()->get()->map(function ($cert) {
            return [
                'id' => $cert->id,
                'file_path' => asset('storage/' . $cert->file_path),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'certificates_updated_successfully',
            'data' => $certificates,
        ]);
    }

    public function manageGallery(Request $request)
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'provider') {
            return response()->json(['success' => false, 'message' => 'unauthorized'], 401);
        }

        $provider = $user->provider;

        if (!$provider) {
            return response()->json(['success' => false, 'message' => 'provider_not_found'], 404);
        }

        $validated = $request->validate([
            'gallery' => 'nullable|array',
            'gallery.*.file' => 'file|mimes:jpeg,jpg,png,mp4,mov|max:51200',
            'gallery.*.description' => 'nullable|string',
            'remove_gallery_ids' => 'nullable|array',
            'remove_gallery_ids.*' => 'integer|exists:portfolio,id',
        ]);

        if (isset($validated['remove_gallery_ids'])) {
            foreach ($validated['remove_gallery_ids'] as $itemId) {
                $item = Portfolio::where('id', $itemId)
                    ->where('provider_id', $provider->id)
                    ->first();

                if ($item) {
                    if (Storage::disk('public')->exists($item->file_path)) {
                        Storage::disk('public')->delete($item->file_path);
                    }
                    $item->delete();
                }
            }
        }

        if ($request->has('gallery')) {
            foreach ($request->input('gallery') as $index => $item) {
                $file = $request->file("gallery.{$index}.file");
                if ($file && $file->isValid()) {
                    $path = 'providers/portfolio/' . uniqid() . '_' . $file->getClientOriginalName();
                    Storage::disk('public')->put($path, file_get_contents($file->getPathname()));
                    $type = in_array($file->getClientOriginalExtension(), ['mp4', 'mov']) ? 'video' : 'image';
                    Portfolio::create([
                        'provider_id' => $provider->id,
                        'file_path' => $path,
                        'file_type' => $type,
                        'description' => $item['description'] ?? null,
                    ]);
                }
            }
        }

        $portfolio = $provider->portfolio()->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'file_path' => asset('storage/' . $item->file_path),
                'file_type' => $item->file_type,
                'description' => $item->description,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'gallery_updated_successfully',
            'data' => $portfolio,
        ]);
    }
}