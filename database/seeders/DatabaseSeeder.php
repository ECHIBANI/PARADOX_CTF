<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Comment;
use App\Models\Tracking;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['phone' => '+212600000000'],
            [
                'name' => 'Admin PARDOX',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        $demoUser = User::updateOrCreate(
            ['phone' => '+212661234567'],
            [
                'name' => 'Karim Benali',
                'password' => Hash::make('user123'),
                'role' => 'user',
            ]
        );

        $vehicles = [
            [
                'name' => 'Dacia Logan',
                'category' => 'Économique',
                'price_per_day' => 220,
                'seats' => 5,
                'transmission' => 'Manuelle',
                'ac' => true,
                'image' => 'https://images.unsplash.com/photo-1541899481282-d53bffe3c35d?w=600&q=80',
            ],
            [
                'name' => 'Renault Clio',
                'category' => 'Économique',
                'price_per_day' => 280,
                'seats' => 5,
                'transmission' => 'Manuelle',
                'ac' => true,
                'image' => 'https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=600&q=80',
            ],
            [
                'name' => 'Peugeot 208',
                'category' => 'Citadine',
                'price_per_day' => 320,
                'seats' => 5,
                'transmission' => 'Automatique',
                'ac' => true,
                'image' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=600&q=80',
            ],
            [
                'name' => 'Volkswagen Golf',
                'category' => 'Citadine',
                'price_per_day' => 380,
                'seats' => 5,
                'transmission' => 'Automatique',
                'ac' => true,
                'image' => 'https://images.unsplash.com/photo-1471479917193-f00955256257?w=600&q=80',
            ],
            [
                'name' => 'Toyota RAV4',
                'category' => 'SUV',
                'price_per_day' => 650,
                'seats' => 7,
                'transmission' => 'Automatique',
                'ac' => true,
                'image' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=600&q=80',
            ],
            [
                'name' => 'Mercedes Classe C',
                'category' => 'Premium',
                'price_per_day' => 950,
                'seats' => 5,
                'transmission' => 'Automatique',
                'ac' => true,
                'image' => 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=600&q=80',
            ],
        ];

        foreach ($vehicles as $vehicleData) {
            Vehicle::updateOrCreate(
                ['name' => $vehicleData['name']],
                $vehicleData
            );
        }

        $sarah = User::updateOrCreate(
            ['phone' => '+212611111111'],
            [
                'name' => 'Sarah L.',
                'password' => Hash::make('password'),
                'role' => 'user',
                'created_at' => '2026-03-01 00:00:00',
            ]
        );

        $jean = User::updateOrCreate(
            ['phone' => '+212622222222'],
            [
                'name' => 'Jean-Pierre M.',
                'password' => Hash::make('password'),
                'role' => 'user',
                'created_at' => '2026-02-01 00:00:00',
            ]
        );

        $amelie = User::updateOrCreate(
            ['phone' => '+212633333333'],
            [
                'name' => 'Amélie R.',
                'password' => Hash::make('password'),
                'role' => 'user',
                'created_at' => '2026-01-01 00:00:00',
            ]
        );

        Comment::updateOrCreate(
            [
                'vehicle_id' => 1,
                'user_id' => $sarah->id,
            ],
            [
                'rating' => 5,
                'body' => 'Super expérience ! La réservation était simple et la voiture parfaite. Je recommande vivement PARDOX.',
                'created_at' => '2026-03-15 00:00:00',
            ]
        );

        Comment::updateOrCreate(
            [
                'vehicle_id' => 2,
                'user_id' => $jean->id,
            ],
            [
                'rating' => 5,
                'body' => 'Service impeccable, aucun frais caché. Le personnel à l\'agence était très accueillant. À refaire !',
                'created_at' => '2026-02-10 00:00:00',
            ]
        );

        Comment::updateOrCreate(
            [
                'vehicle_id' => 5,
                'user_id' => $amelie->id,
            ],
            [
                'rating' => 4,
                'body' => 'Très bon rapport qualité-prix. La Toyota RAV4 était en parfait état. Juste un petit délai à la prise en charge.',
                'created_at' => '2026-01-05 00:00:00',
            ]
        );

        $marrakech = ['lat' => 31.6295, 'lng' => -7.9811];
        $agadir = ['lat' => 30.4278, 'lng' => -9.5981];
        $casablanca = ['lat' => 33.5731, 'lng' => -7.5898];
        $cities = [$marrakech, $agadir, $casablanca];

        foreach (Vehicle::all() as $index => $vehicle) {
            $city = $cities[$index % 3];

            $randLat = $city['lat'] + (mt_rand(-50, 50) / 1000);
            $randLng = $city['lng'] + (mt_rand(-50, 50) / 1000);

            Tracking::updateOrCreate(
                ['vehicle_id' => $vehicle->id],
                [
                    'latitude' => $randLat,
                    'longitude' => $randLng,
                ]
            );
        }
    }
}