<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Information;

class InformasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $informations = [
            [
                'title' => 'Selamat Datang di GriyaOne',
                'content' => 'Platform manajemen aset terpadu yang dirancang untuk memudahkan pengelolaan listing aset Bank Cessie, AYDA, serta lelang dengan antarmuka yang user-friendly dan fitur-fitur canggih.',
                'category' => 'General',
                'status' => 'active',
                'published_date' => now(),
            ],
            [
                'title' => 'Fitur Baru: Video Pembelajaran',
                'content' => 'Kami dengan bangga memperkenalkan fitur baru Video Pembelajaran yang memungkinkan Anda untuk belajar cara menggunakan platform GriyaOne melalui video tutorial interaktif yang mudah dipahami.',
                'category' => 'Update',
                'status' => 'active',
                'published_date' => now()->subDay(),
            ],
            [
                'title' => 'Pengumuman: Maintenance Server',
                'content' => 'Sistem akan melakukan maintenance rutin setiap hari Jumat pukul 22:00 WIB hingga 02:00 WIB untuk memastikan performa optimal. Mohon maaf atas ketidaknyamanannya.',
                'category' => 'Pengumuman',
                'status' => 'active',
                'published_date' => now()->subDays(2),
            ],
            [
                'title' => 'Event Spesial: Workshop Digital Marketing',
                'content' => 'Bergabunglah dengan kami dalam workshop digital marketing gratis yang akan membahas strategi pemasaran aset online di era digital. Pendaftar terbatas hanya untuk 50 peserta pertama.',
                'category' => 'Event',
                'status' => 'active',
                'published_date' => now()->subDays(3),
            ],
            [
                'title' => 'Peningkatan Keamanan Platform',
                'content' => 'Kami telah meningkatkan sistem keamanan platform dengan menambahkan autentikasi dua faktor dan enkripsi data yang lebih kuat untuk melindungi informasi Anda.',
                'category' => 'Update',
                'status' => 'active',
                'published_date' => now()->subDays(4),
            ],
        ];

        foreach ($informations as $information) {
            Information::create($information);
        }
    }
}

