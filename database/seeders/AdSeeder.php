<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdSeeder extends Seeder
{
    public function run()
    {
        $provider = User::where('role', 'provider')->first();

        if (!$provider) {
            return;
        }

        $ads = [
            [
                'provider_id' => $provider->id,
                'image' => 'ads/ad1.jpg',
                'description' => 'خصم 20% على خدمات التنظيف هذا الشهر',
                'is_active' => true,
            ],
            [
                'provider_id' => $provider->id,
                'image' => 'ads/ad2.jpg',
                'description' => 'عرض خاص على خدمات السباكة - اتصل الآن',
                'is_active' => true,
            ],
            [
                'provider_id' => $provider->id,
                'image' => 'ads/ad3.jpg',
                'description' => 'صيانة مكيفات بأسعار مخفضة لفترة محدودة',
                'is_active' => true,
            ],
        ];

        foreach ($ads as $ad) {
            Ad::updateOrCreate(
                ['provider_id' => $ad['provider_id'], 'image' => $ad['image']],
                $ad
            );
        }
    }
}
