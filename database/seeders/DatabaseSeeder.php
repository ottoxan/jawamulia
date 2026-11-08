<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ShieldSeeder::class,
        ]);

        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ])->assignRole('super_admin');

        $user = User::factory()->create([
            'name' => 'Kepala Divisi Marketing',
            'email' => 'mkt@example.com',
        ])->assignRole('mkt_admin');

        $user = User::factory()->create([
            'name' => 'Kepala Gudang Bahan Penolong',
            'email' => 'aux@example.com',
        ])->assignRole('aux_admin');

        $user = User::factory()->create([
            'name' => 'Kepala Gudang Bahan Baku',
            'email' => 'raw@example.com',
        ])->assignRole('raw_admin');

        $user = User::factory()->create([
            'name' => 'Kepala Bagian Produksi',
            'email' => 'prod@example.com',
        ])->assignRole('prod_admin');
    }
}
