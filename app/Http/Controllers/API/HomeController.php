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


                if (!$providerUser) {
                    return null;
                }

                $provider = $providerUser->provider;

                return [
                    'provider_user_id' => $providerUser->id,
                    'name' => $providerUser->name,
                    'photo' => $providerUser->photo ? asset('storage/' . $providerUser->photo) : null,

                    'main_service' => $provider?->mainService ? [
                        'name_ar' => $provider->mainService->name_ar,
                        'name_en' => $provider->mainService->name_en,
                    ] : null,
                    'sub_service' => $provider?->subService ? [
                        'name_ar' => $provider->subService->name_ar,
                        'name_en' => $provider->subService->name_en,
                    ] : null,
                ];
            })
            ->filter()
            ->values();

        $data['favorites'] = $favourites;



        $ads = Ad::where('is_active', true)
            ->with('provider.provider')
            ->get()
            ->map(function ($ad) {
                $providerUser = $ad->provider;


                if (!$providerUser) {
                    return null;
                }

                return [
                    'ad_id' => $ad->id,
                    'provider_user_id' => $providerUser->id,
                    'provider_name' => $providerUser->name,
                    'provider_photo' => $providerUser->photo ? asset('storage/' . $providerUser->photo) : null,
                    'ad_image' => $ad->image ? asset('storage/' . $ad->image) : null,

                    'description' => $ad->description,
                ];
            })
            ->filter()
            ->values();

        $data['ads'] = $ads;

        return response()->json([
            'success' => true,
            'message' => 'Home data retrieved successfully',
            'data' => $data,
        ]);
    }
}