<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Information;
use App\Models\InformationCategory;

class InformasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // This seeder is disabled since Information records are created manually
        // through the admin interface. The category_id is required and must
        // reference existing InformationCategory records.
    }
}

