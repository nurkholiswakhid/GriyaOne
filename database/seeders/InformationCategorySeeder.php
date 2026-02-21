<?php

namespace Database\Seeders;

use App\Models\InformationCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InformationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'General', 'slug' => 'general', 'order' => 1],
            ['name' => 'Update', 'slug' => 'update', 'order' => 2],
            ['name' => 'Pengumuman', 'slug' => 'pengumuman', 'order' => 3],
            ['name' => 'Event', 'slug' => 'event', 'order' => 4],
        ];

        foreach ($categories as $category) {
            InformationCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
