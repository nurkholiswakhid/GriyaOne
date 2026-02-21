<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\MaterialCategory;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories jika belum ada
        $basicCategory = MaterialCategory::firstOrCreate(
            ['slug' => 'basic'],
            ['name' => 'Dasar Properti', 'order' => 1]
        );

        $advancedCategory = MaterialCategory::firstOrCreate(
            ['slug' => 'advanced'],
            ['name' => 'Lanjutan', 'order' => 2]
        );

        $legalCategory = MaterialCategory::firstOrCreate(
            ['slug' => 'legal'],
            ['name' => 'Legal & Hukum', 'order' => 3]
        );

        $materials = [
            // Basic Materials - 5
            ['title' => 'Panduan Lengkap Investasi Properti', 'category' => $basicCategory->id, 'file' => 'materi/basic/panduan-investasi.pdf'],
            ['title' => 'Memahami Jenis-jenis Properti', 'category' => $basicCategory->id, 'file' => 'materi/basic/jenis-properti.pdf'],
            ['title' => 'Cara Menentukan Lokasi Properti Terbaik', 'category' => $basicCategory->id, 'file' => 'materi/basic/memilih-lokasi.pdf'],
            ['title' => 'Analisis Pasar Properti Dasar', 'category' => $basicCategory->id, 'file' => 'materi/basic/analisis-pasar.pdf'],
            ['title' => 'Persiapan Finansial Sebelum Beli Properti', 'category' => $basicCategory->id, 'file' => 'materi/basic/persiapan-finansial.pdf'],

            // Advanced Materials - 5
            ['title' => 'Teknik Due Diligence Properti', 'category' => $advancedCategory->id, 'file' => 'materi/advanced/due-diligence.pdf'],
            ['title' => 'Strategi Flipping Properti', 'category' => $advancedCategory->id, 'file' => 'materi/advanced/flipping-strategy.pdf'],
            ['title' => 'Portfolio Management Properti', 'category' => $advancedCategory->id, 'file' => 'materi/advanced/portfolio-management.pdf'],
            ['title' => 'Cara Mengurus Properti Jarak Jauh', 'category' => $advancedCategory->id, 'file' => 'materi/advanced/remote-management.pdf'],
            ['title' => 'Optimasi Pajak Properti', 'category' => $advancedCategory->id, 'file' => 'materi/advanced/tax-optimization.pdf'],

            // Legal & Hukum - 5
            ['title' => 'Panduan Legal Due Diligence Properti', 'category' => $legalCategory->id, 'file' => 'materi/legal/legal-due-diligence.pdf'],
            ['title' => 'Template Kontrak Jual Beli Properti', 'category' => $legalCategory->id, 'file' => 'materi/legal/kontrak-jualbeli.pdf'],
            ['title' => 'Sertifikat Hak Milik - Penjelasan Lengkap', 'category' => $legalCategory->id, 'file' => 'materi/legal/sertifikat-hm.pdf'],
            ['title' => 'Status Kepemilikan dan Sengketa Properti', 'category' => $legalCategory->id, 'file' => 'materi/legal/status-kepemilikan.pdf'],
            ['title' => 'Pajak Properti Indonesia 2026', 'category' => $legalCategory->id, 'file' => 'materi/legal/pajak-properti-2026.pdf'],
        ];

        foreach ($materials as $material) {
            Material::create([
                'title' => $material['title'],
                'description' => 'Materi pembelajaran lengkap dan komprehensif tentang ' . strtolower($material['title']) . ' untuk investor properti.',
                'category_id' => $material['category'],
                'file_path' => $material['file'],
                'is_published' => true,
            ]);
        }
    }
}
