<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminAdController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'image' => 'required|image|mimes:jpeg,jpg,png|max:5120',
            'description' => 'nullable|string|max:500',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'user_not_found'], 404);
        }

        $imagePath = 'ads/' . uniqid() . '_' . $request->file('image')->getClientOriginalName();
        Storage::disk('public')->put($imagePath, file_get_contents($request->file('image')->getPathname()));

        $ad = Ad::create([
            'provider_id' => $user->id,
            'image' => $imagePath,
            'description' => $validated['description'] ?? null,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'ad_created_successfully',
            'data' => [
                'id' => $ad->id,
                'provider_name' => $user->name,
                'image' => asset('storage/' . $ad->image),
                'description' => $ad->description,
                'created_at' => $ad->created_at->toDateString(),
            ]
        ], 201);
    }

    public function index()
    {
        $ads = Ad::where('is_active', true)
            ->with('provider:id,name,photo')
            ->latest()
            ->get()
            ->map(function ($ad) {
                return [
                    'id' => $ad->id,
                    'provider' => [
                        'id' => $ad->provider?->id,
                        'name' => $ad->provider?->name ?? 'مستخدم محذوف',
                        'photo' => $ad->provider?->photo ?? null,
                    ],
                    'image' => $ad->image,
                    'description' => $ad->description,
                    'created_at' => $ad->created_at->toDateString(),
                ];
            });

        $totalActive = Ad::where('is_active', true)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_active' => $totalActive,
                'ads' => $ads,
            ],
        ]);
    }

    public function destroy($adId)
    {
        $ad = Ad::find($adId);

        if (!$ad) {
            return response()->json(['success' => false, 'message' => 'ad_not_found'], 404);
        }

        if ($ad->image) {
            Storage::disk('public')->delete($ad->image);
        }

        $ad->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ad deleted successfully',
        ]);
    }
}
