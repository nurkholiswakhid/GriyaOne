<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Content;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Video Pembelajaran - Training
        Content::create([
            'title' => 'Cara Merawat Properti Investasi',
            'description' => 'Video panduan lengkap tentang cara merawat properti investasi agar tetap terawat dan nilai properti meningkat seiring waktu.',
            'type' => 'Video',
            'category' => 'Training',
            'file_path' => 'https://www.youtube.com/watch?v=rfscVS0vtbw',
            'duration' => 720,
            'is_published' => true,
            'views' => 450,
        ]);

        Content::create([
            'title' => 'Strategi Investasi Properti Pemula',
            'description' => 'Tutorial untuk pemula yang ingin memulai investasi properti dengan strategi yang tepat dan menguntungkan.',
            'type' => 'Video',
            'category' => 'Training',
            'file_path' => 'https://www.youtube.com/watch?v=kqtD5dpn9C8',
            'duration' => 1200,
            'is_published' => true,
            'views' => 320,
        ]);

        // File Materi - Training
        Content::create([
            'title' => 'Panduan Lengkap Due Diligence Properti',
            'description' => 'File PDF berisi panduan lengkap melakukan due diligence properti sebelum membeli atau menjual.',
            'type' => 'Materi',
            'category' => 'Training',
            'file_path' => 'https://example.com/materi/due-diligence.pdf',
            'is_published' => true,
            'views' => 280,
        ]);

        // Challenge
        Content::create([
            'title' => 'Challenge: Analisis Pasar Properti Q1 2026',
            'description' => 'Tantangan bulanan untuk menganalisis tren pasar properti dan memprediksi pergerakan harga di kuartal pertama 2026.',
            'type' => 'Info',
            'category' => 'Challenge',
            'file_path' => 'https://example.com/challenge',
            'is_published' => true,
            'views' => 180,
        ]);

        Content::create([
            'title' => 'Challenge: Jual Properti dengan Harga Terbaik',
            'description' => 'Tantangan untuk member: siapa yang bisa menjual propertinya dengan harga tertinggi dalam 30 hari akan mendapat bonus eksklusif.',
            'type' => 'Info',
            'category' => 'Challenge',
            'file_path' => null,
            'is_published' => true,
            'views' => 220,
        ]);

        // Bonus Content
        Content::create([
            'title' => 'Template Kontrak Jual Beli Properti Lengkap',
            'description' => 'Template siap pakai untuk kontrak jual beli properti yang telah disusun oleh legal profesional. Eksklusif untuk member premium.',
            'type' => 'Materi',
            'category' => 'Bonus',
            'file_path' => 'https://example.com/template/kontrak-jual-beli.docx',
            'is_published' => true,
            'views' => 520,
        ]);

        Content::create([
            'title' => 'Webinar Eksklusif: Tren Properti 2026',
            'description' => 'Webinar spesial menghadirkan expert properti membahas tren pasar dan peluang investasi di tahun 2026. Bonus eksklusif untuk peserta.',
            'type' => 'Video',
            'category' => 'Bonus',
            'file_path' => 'https://www.youtube.com/watch?v=pTB0EiLXUC8',
            'duration' => 1800,
            'is_published' => true,
            'views' => 850,
        ]);

        // Latest Info
        Content::create([
            'title' => 'Update: Program Relaksasi Bunga KPR Tahun 2026',
            'description' => 'Kabar gembira! Pemerintah telah mengeluarkan program relaksasi bunga KPR untuk tahun 2026. Pelajari detail lengkapnya di sini.',
            'type' => 'Info',
            'category' => null,
            'is_published' => true,
            'views' => 920,
        ]);

        Content::create([
            'title' => 'Fitur Baru: Virtual Tour 360 Derajat untuk Properti',
            'description' => 'Kami telah meluncurkan fitur virtual tour 360 derajat. Kini calon pembeli dapat melihat properti Anda lebih detail dan menarik.',
            'type' => 'Info',
            'category' => null,
            'is_published' => true,
            'views' => 650,
        ]);

        Content::create([
            'title' => 'Tips: Optimalkan Listing Properti Anda untuk Lebih Cepat Terjual',
            'description' => 'Pelajari tips dan trik dari tim ahli kami untuk mengoptimalkan listing properti Anda agar lebih menarik dan cepat terjual.',
            'type' => 'Info',
            'category' => null,
            'is_published' => true,
            'views' => 740,
        ]);
    }
}
