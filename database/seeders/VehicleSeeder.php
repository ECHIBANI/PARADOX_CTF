<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

/**
 * VehicleSeeder — Seeds 6 demo vehicles.
 * Run with: php artisan db:seed --class=VehicleSeeder
 */
class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = [
            [
                'name'         => 'Peugeot 208',
                'category'     => 'Economique',
                'price_per_day' => 35.00,
                'seats'        => 5,
                'transmission' => 'Automatique',
                'has_ac'       => true,
                'image_url'    => 'https://images.unsplash.com/photo-1609521263047-f8f205293f24?w=600&q=80',
                'description'  => 'Citadine parfaite pour la ville. Économique et agile.',
                'available'    => true,
            ],
            [
                'name'         => 'Toyota RAV4',
                'category'     => 'SUV',
                'price_per_day' => 65.00,
                'seats'        => 5,
                'transmission' => 'Automatique',
                'has_ac'       => true,
                'image_url'    => 'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?w=600&q=80',
                'description'  => 'SUV spacieux et polyvalent pour les voyages en famille.',
                'available'    => true,
            ],
            [
                'name'         => 'Tesla Model 3',
                'category'     => 'Prestige',
                'price_per_day' => 65.00,
                'seats'        => 5,
                'transmission' => 'Automatique',
                'has_ac'       => true,
                'image_url'    => 'https://images.unsplash.com/photo-1560958089-b8a1929cea89?w=600&q=80',
                'description'  => 'Véhicule électrique premium avec technologie de pointe.',
                'available'    => true,
            ],
            [
                'name'         => 'Volkswagen ID.4',
                'category'     => 'SUV',
                'price_per_day' => 72.00,
                'seats'        => 5,
                'transmission' => 'Automatique',
                'has_ac'       => true,
                'image_url'    => 'https://images.unsplash.com/photo-1617788138017-80ad40651399?w=600&q=80',
                'description'  => 'SUV électrique moderne pour une conduite éco-responsable.',
                'available'    => true,
            ],
            [
                'name'         => 'Renault Clio',
                'category'     => 'Economique',
                'price_per_day' => 28.00,
                'seats'        => 5,
                'transmission' => 'Manuelle',
                'has_ac'       => true,
                'image_url'    => 'https://images.unsplash.com/photo-1541899481282-d53bffe3c35d?w=600&q=80',
                'description'  => 'La petite citadine française par excellence.',
                'available'    => true,
            ],
            [
                'name'         => 'Mercedes Classe E',
                'category'     => 'Prestige',
                'price_per_day' => 120.00,
                'seats'        => 5,
                'transmission' => 'Automatique',
                'has_ac'       => true,
                'image_url'    => 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=600&q=80',
                'description'  => 'Berline de prestige pour vos déplacements professionnels.',
                'available'    => true,
            ],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::create($vehicle);
        }

        $this->command->info('✅ 6 vehicles seeded successfully.');
    }
}
