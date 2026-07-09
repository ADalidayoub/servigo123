<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\AdminChat;
use App\Models\AdminMessage;
use App\Models\Admin;
use App\Models\Certificate;
use App\Models\Chat;
use App\Models\CommentReport;
use App\Models\Complaint;
use App\Models\Favourite;
use App\Models\Message;
use App\Models\OffDay;
use App\Models\Portfolio;
use App\Models\Provider;
use App\Models\Rating;
use App\Models\SearchLog;
use App\Models\Service;
use App\Models\SubService;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Seeds the database with a large amount of realistic fake data
     * for local testing / frontend integration purposes.
     *
     * All generated locations/addresses are restricted to Syrian cities only.
     *
     * Run with: php artisan db:seed --class=DemoDataSeeder
     */
    public function run(): void
    {
        // ---------------------------------------------------------------
        // 0. Make sure base lookup data (services / sub-services / admin) exists
        // ---------------------------------------------------------------
        if (Service::count() === 0) {
            $this->call(ServiceSeeder::class);
        }
        if (SubService::count() === 0) {
            $this->call(SubServicesSeeder::class);
        }
        if (Admin::count() === 0) {
            $this->call(AdminSeeder::class);
        }

        $admin = Admin::first();
        $services = Service::with('subServices')->get();

        $daysOfWeek = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        // Syrian cities + their approximate real coordinates (kept inside Syria only)
        $syrianCities = [
            'دمشق'        => [33.5138, 36.2765],
            'حلب'         => [36.2021, 37.1343],
            'حمص'         => [34.7324, 36.7137],
            'حماة'        => [35.1318, 36.7430],
            'اللاذقية'     => [35.5317, 35.7915],
            'طرطوس'       => [34.8959, 35.8866],
            'دير الزور'    => [35.3359, 40.1408],
            'الرقة'        => [35.9594, 39.0079],
            'الحسكة'      => [36.5024, 40.7477],
            'درعا'        => [32.6189, 36.1021],
            'السويداء'     => [32.7094, 36.5694],
            'القنيطرة'     => [33.1264, 35.8244],
            'إدلب'        => [35.9306, 36.6339],
            'ريف دمشق'    => [33.5100, 36.4000],
        ];

        // ---------------------------------------------------------------
        // 1. Regular users (role = user)
        // ---------------------------------------------------------------
        $users = User::factory()
            ->count(40)
            ->create(['role' => 'user']);

        // Always have one predictable test account
        $testUser = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'مستخدم تجريبي',
                'phone' => '0999999999',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_banned' => false,
            ]
        );
        $users->push($testUser);

        // ---------------------------------------------------------------
        // 2. Provider users + provider profiles
        // ---------------------------------------------------------------
        $providerUsers = collect();

        for ($i = 0; $i < 25; $i++) {
            $service = $services->random();
            $subService = $service->subServices->isNotEmpty()
                ? $service->subServices->random()
                : null;

            // Location is always picked from the Syrian cities list above,
            // so coordinates and city names never leave Syria.
            $cityName = array_rand($syrianCities);
            [$cityLat, $cityLng] = $syrianCities[$cityName];

            $providerUser = User::factory()->create([
                'role' => 'provider',
            ]);

            $status = fake()->randomElement(['approved', 'approved', 'approved', 'pending', 'rejected']);

            Provider::create([
                'user_id' => $providerUser->id,
                'location_name' => $cityName,
                // small random offset (max ~3km) so providers aren't all on the exact same point, while staying inside the city/Syria
                'latitude' => $cityLat + fake()->randomFloat(5, -0.03, 0.03),
                'longitude' => $cityLng + fake()->randomFloat(5, -0.03, 0.03),
                'work_type' => fake()->randomElement(['fixed', 'mobile', 'both']),
                'main_service_id' => $service->id,
                'sub_service_id' => $subService?->id,
                'id_photo_front' => 'providers/id_photos/servigo.jpg',
                'id_photo_back' => 'providers/id_photos/servigo.jpg',
                'status' => $status,
                'rejection_reason' => $status === 'rejected' ? fake()->sentence() : null,
                'profile_completed' => $status !== 'pending',
                'location_description' => 'حي ' . fake()->lastName() . '، ' . $cityName,
                'currency' => fake()->randomElement(['USD', 'SYP']),
                'min_price' => $minPrice = fake()->numberBetween(10, 100),
                'max_price' => $minPrice + fake()->numberBetween(20, 300),
                'work_start_time' => '09:00:00',
                'work_end_time' => '18:00:00',
                'overnight' => fake()->boolean(20),
                'about_me' => fake()->realText(150),
                'is_available' => fake()->boolean(80),
            ]);

            $providerUsers->push($providerUser);
        }

        // One predictable provider for manual frontend testing
        $testProviderUser = User::firstOrCreate(
            ['email' => 'provider@example.com'],
            [
                'name' => 'مقدم خدمة تجريبي',
                'phone' => '123456789',
                'password' => Hash::make('password'),
                'role' => 'provider',
                'is_banned' => false,
            ]
        );

        Provider::updateOrCreate(
            ['user_id' => $testProviderUser->id],
            [
                'location_name' => 'دمشق',
                'latitude' => 33.5138,
                'longitude' => 36.2765,
                'work_type' => 'both',
                'main_service_id' => $services->first()->id,
                'sub_service_id' => $services->first()->subServices->first()?->id,
                'status' => 'approved',
                'profile_completed' => true,
                'is_available' => true,
                'min_price' => 50,
                'max_price' => 200,
                'currency' => 'USD',
                'work_start_time' => '09:00:00',
                'work_end_time' => '18:00:00',
                'overnight' => false,
                'about_me' => 'provider test',
                'id_photo_front' => 'providers/id_photos/servigo.jpg',
                'id_photo_back' => 'providers/id_photos/servigo.jpg',
            ]
        );
        $providerUsers->push($testProviderUser);

        $approvedProviders = Provider::where('status', 'approved')->get();

        // ---------------------------------------------------------------
        // 3. Off days, certificates, portfolio items for each provider
        // ---------------------------------------------------------------
        foreach (Provider::all() as $provider) {
            foreach (fake()->randomElements($daysOfWeek, fake()->numberBetween(0, 2)) as $day) {
                OffDay::firstOrCreate([
                    'provider_id' => $provider->id,
                    'day' => $day,
                ]);
            }

            for ($c = 0; $c < fake()->numberBetween(0, 2); $c++) {
                Certificate::create([
                    'provider_id' => $provider->id,
                    'file_path' => 'certificates/servigo.jpg',
                ]);
            }

            for ($p = 0; $p < fake()->numberBetween(1, 4); $p++) {
                Portfolio::create([
                    'provider_id' => $provider->id,
                    'file_path' => 'portfolio/servigo.jpg',
                    'file_type' => 'image',
                    'description' => fake()->sentence(),
                ]);
            }
        }

        // ---------------------------------------------------------------
        // 4. Ratings (provider_id references the USER id of the provider)
        // ---------------------------------------------------------------
        $usedRatingPairs = [];
        $ratings = collect();

        for ($i = 0; $i < 80; $i++) {
            $provider = $approvedProviders->random();
            $rater = $users->random();

            $key = $provider->user_id . '-' . $rater->id;
            if (isset($usedRatingPairs[$key])) {
                continue;
            }
            $usedRatingPairs[$key] = true;

            $ratings->push(Rating::create([
                'provider_id' => $provider->user_id,
                'user_id' => $rater->id,
                'rating' => fake()->numberBetween(1, 5),
                'review' => fake()->boolean(70) ? fake()->realText(120) : null,
            ]));
        }

        // ---------------------------------------------------------------
        // 5. Favourites
        // ---------------------------------------------------------------
        $usedFavPairs = [];
        for ($i = 0; $i < 50; $i++) {
            $provider = $approvedProviders->random();
            $user = $users->random();

            $key = $user->id . '-' . $provider->user_id;
            if (isset($usedFavPairs[$key])) {
                continue;
            }
            $usedFavPairs[$key] = true;

            Favourite::create([
                'user_id' => $user->id,
                'provider_id' => $provider->user_id,
            ]);
        }

        // ---------------------------------------------------------------
        // 6. Chats + Messages (between a user and a provider)
        // ---------------------------------------------------------------
        $usedChatPairs = [];
        for ($i = 0; $i < 25; $i++) {
            $user = $users->random();
            $providerUser = $providerUsers->random();

            if ($user->id === $providerUser->id) {
                continue;
            }

            $p1 = min($user->id, $providerUser->id);
            $p2 = max($user->id, $providerUser->id);
            $key = $p1 . '-' . $p2;
            if (isset($usedChatPairs[$key])) {
                continue;
            }
            $usedChatPairs[$key] = true;

            $chat = Chat::create([
                'type' => 'private',
                'participant_one' => $p1,
                'participant_two' => $p2,
            ]);

            foreach (range(1, fake()->numberBetween(2, 8)) as $m) {
                Message::create([
                    'chat_id' => $chat->id,
                    'sender_id' => fake()->randomElement([$p1, $p2]),
                    'content' => fake()->realText(80),
                ]);
            }
        }

        // ---------------------------------------------------------------
        // 7. Complaints
        // ---------------------------------------------------------------
        for ($i = 0; $i < 20; $i++) {
            $provider = $approvedProviders->random();
            $user = $users->random();

            Complaint::create([
                'user_id' => $user->id,
                'provider_id' => $provider->user_id,
                'message' => fake()->realText(150),
                'status' => fake()->randomElement(['pending', 'processed']),
            ]);
        }

        // ---------------------------------------------------------------
        // 8. Ads for a handful of providers
        // ---------------------------------------------------------------
        foreach ($approvedProviders->random(min(10, $approvedProviders->count())) as $provider) {
            foreach (range(1, fake()->numberBetween(1, 3)) as $a) {
                Ad::create([
                    'provider_id' => $provider->user_id,
                    'image' => 'ads/servigo.jpg',
                    'description' => fake()->sentence(),
                    'is_active' => fake()->boolean(85),
                ]);
            }
        }

        // ---------------------------------------------------------------
        // 9. Comment reports (on some ratings that have a review)
        // ---------------------------------------------------------------
        foreach ($ratings->where('review', '!=', null)->random(min(10, $ratings->count())) as $rating) {
            CommentReport::create([
                'rating_id' => $rating->id,
                'provider_id' => $rating->provider_id,
                'reason' => fake()->randomElement([
                    'محتوى غير لائق',
                    'معلومات مضللة',
                    'إساءة لفظية',
                    'سبام أو إعلان',
                ]),
            ]);
        }

        // ---------------------------------------------------------------
        // 10. Admin chats + admin messages
        // ---------------------------------------------------------------
        if ($admin) {
            foreach ($users->random(min(10, $users->count())) as $user) {
                $adminChat = AdminChat::firstOrCreate([
                    'admin_id' => $admin->id,
                    'user_id' => $user->id,
                ]);

                foreach (range(1, fake()->numberBetween(1, 5)) as $m) {
                    $senderType = fake()->randomElement(['admin', 'user']);
                    AdminMessage::create([
                        'admin_chat_id' => $adminChat->id,
                        'sender_type' => $senderType,
                        'sender_id' => $senderType === 'admin' ? $admin->id : $user->id,
                        'content' => fake()->realText(60),
                    ]);
                }
            }
        }

        // ---------------------------------------------------------------
        // 11. Search logs
        // ---------------------------------------------------------------
        for ($i = 0; $i < 60; $i++) {
            $service = $services->random();
            $subService = $service->subServices->isNotEmpty() ? $service->subServices->random() : null;

            if (!$subService) {
                continue;
            }

            SearchLog::create([
                'user_id' => $users->random()->id,
                'main_service_id' => $service->id,
                'sub_service_id' => $subService->id,
            ]);
        }

        $this->command->info('✅ Demo data seeded successfully (Syria-only locations).');
        $this->command->info('Test accounts -> user@example.com / provider@example.com / admin@servigo.com (password: password / password / 12345678)');
    }
}
