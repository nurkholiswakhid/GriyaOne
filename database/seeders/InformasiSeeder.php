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
        $categories = InformationCategory::all();

        if ($categories->isEmpty()) {
            return; // Jika kategori belum ada, skip seeder ini
        }

        $informations = [
            // General - 3
            ['title' => 'Selamat Datang di Platform GriyaOne', 'category_id' => 1, 'content' => 'Platform terpercaya untuk investasi properti dengan ribuan listing berkualitas dan analisis pasar terdepan.'],
            ['title' => 'Cara Menggunakan Dashboard Admin', 'category_id' => 1, 'content' => 'Panduan lengkap untuk menggunakan semua fitur dashboard admin di platform GriyaOne.'],
            ['title' => 'Kebijakan Privasi dan Keamanan Data', 'category_id' => 1, 'content' => 'Kami berkomitmen menjaga privasi dan keamanan data member dengan enkripsi tingkat enterprise.'],

            // Update - 5
            ['title' => 'Update: Fitur Virtual Tour 360 Diluncurkan', 'category_id' => 2, 'content' => 'Virtual tour 360 derajat kini tersedia untuk semua listing properti. Nikmati pengalaman viewing yang lebih immersive.'],
            ['title' => 'API Integration dengan Portal Properti Besar', 'category_id' => 2, 'content' => 'GriyaOne kini terintegrasi dengan portal properti terbesar se-Asia Tenggara untuk jangkauan listing lebih luas.'],
            ['title' => 'Sistem Pembayaran Digital Terbaru', 'category_id' => 2, 'content' => 'Kami menambahkan opsi pembayaran terbaru termasuk cryptocurrency untuk fleksibilitas maksimal member.'],
            ['title' => 'Mobile App v2.5 Dengan Fitur AI', 'category_id' => 2, 'content' => 'Aplikasi mobile terbaru dilengkapi AI untuk rekomendasi properti yang lebih akurat berdasarkan preferensi Anda.'],
            ['title' => 'Ekspansi Server Global', 'category_id' => 2, 'content' => 'Platform GriyaOne kini hadir di 15 negara dengan server lokal untuk performa maksimal di setiap region.'],

            // Pengumuman - 4
            ['title' => 'Promo Diskon Listing Properti 50%', 'category_id' => 3, 'content' => 'Dapatkan diskon hingga 50% untuk listing properti hingga akhir bulan ini. Kesempatan terbatas untuk 100 listing pertama!'],
            ['title' => 'Webinar Gratis: Investasi Properti Aman', 'category_id' => 3, 'content' => 'Webinar interaktif membahas strategi investasi properti yang aman dan menguntungkan. Daftar sekarang - gratis!'],
            ['title' => 'Sertifikasi Agen Properti Profesional', 'category_id' => 3, 'content' => 'Program sertifikasi profesional kini dibuka untuk agen dan broker properti. Daftar sekarang di website kami.'],
            ['title' => 'Penutupan Maintenance Server 24 Jam', 'category_id' => 3, 'content' => 'Server akan di-maintenance 24 jam pada tanggal 25 Februari 2026. Platform akan tidak dapat diakses sementara.'],

            // Event - 3
            ['title' => 'Property Expo 2026 - Jakarta Convention Center', 'category_id' => 4, 'content' => 'Pameran properti terbesar tahun ini dengan ratusan developer dan ribuan listing eksklusif. Jangan lewatkan!'],
            ['title' => 'Workshop Gratis: Strategi Branding Properti', 'category_id' => 4, 'content' => 'Workshop satu hari membahas cara branding dan marketing properti secara efektif dan budget-friendly.'],
            ['title' => 'Networking Event: Investor & Developer Properti', 'category_id' => 4, 'content' => 'Kesempatan networking langsung dengan investor kaya dan developer besar. Daftar sekarang dengan gratis!'],
        ];

        foreach ($informations as $info) {
            Information::create([
                'title' => $info['title'],
                'content' => $info['content'],
                'category_id' => $info['category_id'],
                'status' => 'active',
                'published_date' => now(),
            ]);
        }
    }
}

