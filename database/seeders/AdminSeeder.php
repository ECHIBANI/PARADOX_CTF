<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@carrentexpress.fr'],
            [
                'name'     => 'Administrateur',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
            ]
        );
        $this->command->info('✅ Admin user created: admin@carrentexpress.fr / admin123');
    }
}
