<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Address;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Production ortamında test verileri oluşturmayı engelle
        if (app()->environment('production')) {
            $this->command->warn('⚠️  Production ortamında sadece gerekli seeder\'lar çalıştırılıyor...');
            $this->call([
                RolesPermissionsSeeder::class,
            ]);
            return;
        }

        $this->call([
            UsersSeeder::class,
            RolesPermissionsSeeder::class,
        ]);

        \App\Models\User::factory(20)->create();

        Address::factory(20)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
