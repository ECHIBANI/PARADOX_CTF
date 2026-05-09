<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = [
            // ─── Économique ───────────────────────────────────────────────
            [
                'name'          => 'Dacia Logan',
                'category'      => 'Économique',
                'price_per_day' => 200,
                'seats'         => 5,
                'transmission'  => 'Manuelle',
                'ac'            => true,
                'available'     => true,
                'description'   => 'Idéale pour les petits budgets, fiable et économique en carburant.',
                'image'         => 'https://images.unsplash.com/photo-1541899481282-d53bffe3c35d?w=600&q=80',
            ],
            [
                'name'          => 'Dacia Sandero',
                'category'      => 'Économique',
                'price_per_day' => 220,
                'seats'         => 5,
                'transmission'  => 'Manuelle',
                'ac'            => true,
                'available'     => true,
                'description'   => 'Compacte et maniable, parfaite pour la ville et les courts trajets.',
                'image'         => 'https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=600&q=80',
            ],
            [
                'name'          => 'Renault Clio',
                'category'      => 'Économique',
                'price_per_day' => 260,
                'seats'         => 5,
                'transmission'  => 'Manuelle',
                'ac'            => true,
                'available'     => true,
                'description'   => 'Citadine moderne avec un excellent confort de conduite.',
                'image'         => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=600&q=80',
            ],
            // ─── Citadine ─────────────────────────────────────────────────
            [
                'name'          => 'Peugeot 208',
                'category'      => 'Citadine',
                'price_per_day' => 320,
                'seats'         => 5,
                'transmission'  => 'Automatique',
                'ac'            => true,
                'available'     => true,
                'description'   => 'Design élégant et technologie embarquée de dernière génération.',
                'image'         => 'https://images.unsplash.com/photo-1471479917193-f00955256257?w=600&q=80',
            ],
            [
                'name'          => 'Volkswagen Polo',
                'category'      => 'Citadine',
                'price_per_day' => 350,
                'seats'         => 5,
                'transmission'  => 'Automatique',
                'ac'            => true,
                'available'     => true,
                'description'   => 'Compacte allemande reconnue pour sa robustesse et son confort.',
                'image'         => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=600&q=80',
            ],
            [
                'name'          => 'Toyota Yaris',
                'category'      => 'Citadine',
                'price_per_day' => 300,
                'seats'         => 5,
                'transmission'  => 'Automatique',
                'ac'            => true,
                'available'     => true,
                'description'   => 'Hybride fiable, très économique en carburant en ville.',
                'image'         => 'https://images.unsplash.com/photo-1606016159991-dfe4f2746ad5?w=600&q=80',
            ],
            // ─── Berline / Familiale ──────────────────────────────────────
            [
                'name'          => 'Volkswagen Golf',
                'category'      => 'Citadine',
                'price_per_day' => 400,
                'seats'         => 5,
                'transmission'  => 'Automatique',
                'ac'            => true,
                'available'     => true,
                'description'   => 'La référence des compactes, confort supérieur et tenue de route impeccable.',
                'image'         => 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=600&q=80',
            ],
            [
                'name'          => 'Hyundai Tucson',
                'category'      => 'SUV',
                'price_per_day' => 550,
                'seats'         => 5,
                'transmission'  => 'Automatique',
                'ac'            => true,
                'available'     => true,
                'description'   => 'SUV moderne avec grand espace intérieur et équipements high-tech.',
                'image'         => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=600&q=80',
            ],
            // ─── SUV ──────────────────────────────────────────────────────
            [
                'name'          => 'Toyota RAV4',
                'category'      => 'SUV',
                'price_per_day' => 650,
                'seats'         => 7,
                'transmission'  => 'Automatique',
                'ac'            => true,
                'available'     => true,
                'description'   => 'SUV robuste idéal pour les familles et les voyages longue distance.',
                'image'         => 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=600&q=80',
            ],
            [
                'name'          => 'Nissan X-Trail',
                'category'      => 'SUV',
                'price_per_day' => 600,
                'seats'         => 7,
                'transmission'  => 'Automatique',
                'ac'            => true,
                'available'     => true,
                'description'   => '7 places, parfait pour les grands groupes et les excursions tout-terrain.',
                'image'         => 'https://images.unsplash.com/photo-1562159749-523a8fd43041?w=600&q=80',
            ],
            [
                'name'          => 'Kia Sportage',
                'category'      => 'SUV',
                'price_per_day' => 580,
                'seats'         => 5,
                'transmission'  => 'Automatique',
                'ac'            => true,
                'available'     => true,
                'description'   => 'Design moderne et dynamique, technologie avancée embarquée.',
                'image'         => 'https://images.unsplash.com/photo-1548103843-cd893c02716c?w=600&q=80',
            ],
            // ─── Premium ──────────────────────────────────────────────────
            [
                'name'          => 'Mercedes Classe C',
                'category'      => 'Premium',
                'price_per_day' => 950,
                'seats'         => 5,
                'transmission'  => 'Automatique',
                'ac'            => true,
                'available'     => true,
                'description'   => 'Berline de luxe alliant élégance, puissance et technologies premium.',
                'image'         => 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=600&q=80',
            ],
            [
                'name'          => 'BMW Série 3',
                'category'      => 'Premium',
                'price_per_day' => 1000,
                'seats'         => 5,
                'transmission'  => 'Automatique',
                'ac'            => true,
                'available'     => true,
                'description'   => 'Expérience de conduite ultime avec finition haut de gamme.',
                'image'         => 'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=600&q=80',
            ],
            [
                'name'          => 'Audi A4',
                'category'      => 'Premium',
                'price_per_day' => 980,
                'seats'         => 5,
                'transmission'  => 'Automatique',
                'ac'            => true,
                'available'     => true,
                'description'   => 'Sophistication allemande avec habitacle ultra-raffiné et silence de roulement.',
                'image'         => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=600&q=80',
            ],
            // ─── Utilitaire ───────────────────────────────────────────────
            [
                'name'          => 'Mercedes Vito',
                'category'      => 'SUV',
                'price_per_day' => 750,
                'seats'         => 9,
                'transmission'  => 'Manuelle',
                'ac'            => true,
                'available'     => true,
                'description'   => 'Minibus 9 places idéal pour les transferts aéroport et groupes.',
                'image'         => 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?w=600&q=80',
            ],
        ];

        foreach ($vehicles as $vehicle) {
            // Éviter les doublons si le seeder est relancé
            Vehicle::firstOrCreate(
                ['name' => $vehicle['name']],
                $vehicle
            );
        }

        $this->command->info('✅ ' . count($vehicles) . ' véhicules insérés avec succès !');
    }
}
