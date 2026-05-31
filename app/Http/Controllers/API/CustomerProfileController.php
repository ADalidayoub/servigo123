<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerProfileController extends Controller
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

        if (!$user || $user->role !== 'user') {
            return response()->json(['success' => false, 'message' => 'unauthorized'], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'photo' => $this->getUserPhoto($user),
            ]
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'user') {
            return response()->json(['success' => false, 'message' => 'unauthorized'], 401);
        }

        $validated = $request->validate([
            'name' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        if (isset($validated['name'])) {
            $user->name = $validated['name'];
        }
        if (isset($validated['phone'])) {
            $user->phone = $validated['phone'];
        }
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'profile_updated_successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'photo' => $this->getUserPhoto($user),
            ]
        ]);
    }

    public function updateAvatar(Request $request)
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'user') {
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
}
