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
            'users_name' => '1234.csv',
            'path' => '/usr/local/bin/csv',

        ]);
        Dataset::create([
            'owner_id' => 2,
            'users_name' => '12222.csv',
            'path' => '/usr/local/bin/csv',
        ]);
    }
}
