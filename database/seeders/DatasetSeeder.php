<?php

namespace Database\Seeders;

use App\Models\Dataset;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatasetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dataset::create([
            'owner_id' => 1,
            'external_id' => 1,
            'email' => 'akvar@gmail.com',
            'full_name' => 'Akvar',
            'country_code' => 'US',
            'company_id' => 1,

        ]);
        Dataset::create([
            'owner_id' => 2,
            'external_id' => 2,
            'email' => 'akvar123@gmail.com',
            'full_name' => 'Akvar123',
            'country_code' => 'PL',
            'company_id' => 2,
        ]);
    }
}
