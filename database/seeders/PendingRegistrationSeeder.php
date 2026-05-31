<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Provider;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PendingRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceId = Service::first()?->id ?? 1;

        // Pending providers (for /registrations/pending)
        $pendingProviders = [
            [
                'name'     => 'Ahmad Ali',
                'phone'    => '0912345678',
                'email'    => 'ahmad@example.com',
                'location' => 'Damascus',
                'lat'      => 33.5138073,
                'lng'      => 36.2765279,
            ],
            [
                'name'     => 'Khaled Hassan',
                'phone'    => '0934567890',
                'email'    => 'khaled@example.com',
                'location' => 'Aleppo',
                'lat'      => 36.2021000,
                'lng'      => 37.1343000,
            ],
        ];

        foreach ($pendingProviders as $data) {
            $user = User::create([
                'name'     => $data['name'],
                'phone'    => $data['phone'],
                'email'    => $data['email'],
                'password' => Hash::make('12345678'),
                'role'     => 'provider',
            ]);

            Provider::create([
                'user_id'          => $user->id,
                'location_name'    => $data['location'],
                'latitude'         => $data['lat'],
                'longitude'        => $data['lng'],
                'work_type'        => 'both',
                'main_service_id'  => $serviceId,
                'status'           => 'pending',
                'profile_completed'=> false,
            ]);
        }

        // Approved providers (for /providers)
        $approvedProviders = [
            [
                'name'     => 'Sara Mohamed',
                'phone'    => '0923456789',
                'email'    => 'sara@example.com',
                'location' => 'Latakia',
                'lat'      => 35.5317000,
                'lng'      => 35.7918000,
                'banned'   => false,
                'complete' => true,
            ],
            [
                'name'     => 'Fatima Youssef',
                'phone'    => '0945678901',
                'email'    => 'fatima@example.com',
                'location' => 'Homs',
                'lat'      => 34.7324000,
                'lng'      => 36.7137000,
                'banned'   => false,
                'complete' => true,
            ],
            [
                'name'     => 'Omar Khalil',
                'phone'    => '0956789012',
                'email'    => 'omar@example.com',
                'location' => 'Damascus',
                'lat'      => 33.5100000,
                'lng'      => 36.2900000,
                'banned'   => true,
                'complete' => true,
            ],
            [
                'name'     => 'Lina Saleh',
                'phone'    => '0967890123',
                'email'    => 'lina@example.com',
                'location' => 'Aleppo',
                'lat'      => 36.2000000,
                'lng'      => 37.1500000,
                'banned'   => false,
                'complete' => false,
            ],
        ];

        foreach ($approvedProviders as $data) {
            $user = User::create([
                'name'     => $data['name'],
                'phone'    => $data['phone'],
                'email'    => $data['email'],
                'password' => Hash::make('12345678'),
                'role'     => 'provider',
                'is_banned'=> $data['banned'],
            ]);

            Provider::create([
                'user_id'          => $user->id,
                'location_name'    => $data['location'],
                'latitude'         => $data['lat'],
                'longitude'        => $data['lng'],
                'work_type'        => 'both',
                'main_service_id'  => $serviceId,
                'status'           => 'approved',
                'profile_completed'=> $data['complete'],
            ]);
        }
    }
}
