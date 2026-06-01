<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Favourite;
use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $mainServices = Service::select('id', 'name_ar', 'name_en', 'photo')->get();

        $data = [
            'main_services' => $mainServices,
        ];

        $favourites = Favourite::where('user_id', $user->id)
            ->with(['provider.provider.mainService', 'provider.provider.subService'])
            ->get()
            ->map(function ($fav) {
                $providerUser = $fav->provider;
                $provider = $providerUser?->provider;

                return [
                    'provider_user_id' => $providerUser->id,
                    'name' => $providerUser->name,
                    'photo' => $providerUser->photo ?? null,
                    'main_service' => $provider?->mainService ? [
                        'name_ar' => $provider->mainService->name_ar,
                        'name_en' => $provider->mainService->name_en,
                    ] : null,
                    'sub_service' => $provider?->subService ? [
                        'name_ar' => $provider->subService->name_ar,
                        'name_en' => $provider->subService->name_en,
                    ] : null,
                ];
            });

        if ($favourites->isNotEmpty()) {
            $data['favorites'] = $favourites;
        }

        $ads = Ad::where('is_active', true)
            ->with('provider.provider')
            ->get()
            ->map(function ($ad) {
                $providerUser = $ad->provider;
                $providerProfile = $providerUser?->provider;

                return [
                    'ad_id' => $ad->id,
                    'provider_user_id' => $providerUser->id,
                    'provider_name' => $providerUser->name,
                    'provider_photo' => $providerUser->photo ?? null,
                    'ad_image' => $ad->image,
                    'description' => $ad->description,
                ];
            });

        $data['ads'] = $ads;

        return response()->json([
            'success' => true,
            'message' => 'Home data retrieved successfully',
            'data' => $data,
        ]);
    }
}