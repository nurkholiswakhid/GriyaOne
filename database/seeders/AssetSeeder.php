<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Asset;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assets = [
            // Bank Cessie - 5 properti
            ['title' => 'Rumah Mewah di Jakarta Selatan', 'category' => 'Bank Cessie', 'status' => 'Available', 'location' => 'Jakarta Selatan, DKI Jakarta'],
            ['title' => 'Rumah Cluster di Kawasan Berkembang', 'category' => 'Bank Cessie', 'status' => 'Sold Out', 'location' => 'Depok, Jawa Barat'],
            ['title' => 'Rumah Tropis Modern Bogor', 'category' => 'Bank Cessie', 'status' => 'Available', 'location' => 'Bogor, Jawa Barat'],
            ['title' => 'Villa Mewah di Sentul', 'category' => 'Bank Cessie', 'status' => 'Available', 'location' => 'Sentul, Bogor'],
            ['title' => 'Rumah Minimalis di Tangerang', 'category' => 'Bank Cessie', 'status' => 'Available', 'location' => 'Tangerang Selatan, Banten'],

            // AYDA - 5 properti
            ['title' => 'Apartemen Modern di Pusat Kota', 'category' => 'AYDA', 'status' => 'Available', 'location' => 'Jakarta Pusat, DKI Jakarta'],
            ['title' => 'Ruang Perkantoran di Gedung Komersial', 'category' => 'AYDA', 'status' => 'Available', 'location' => 'Jakarta Barat, DKI Jakarta'],
            ['title' => 'Apartemen High-Rise di Senayan', 'category' => 'AYDA', 'status' => 'Available', 'location' => 'Jakarta Selatan, DKI Jakarta'],
            ['title' => 'Ruko Komersial 3 Lantai', 'category' => 'AYDA', 'status' => 'Sold Out', 'location' => 'Kelapa Gading, Jakarta Utara'],
            ['title' => 'Apartemen Grand Indonesia', 'category' => 'AYDA', 'status' => 'Available', 'location' => 'Jakarta Pusat, DKI Jakarta'],

            // Lelang - 5 properti
            ['title' => 'Tanah Komersial Lokasi Strategis', 'category' => 'Lelang', 'status' => 'Available', 'location' => 'Bandung, Jawa Barat'],
            ['title' => 'Tanah Kavling Surabaya', 'category' => 'Lelang', 'status' => 'Available', 'location' => 'Surabaya, Jawa Timur'],
            ['title' => 'Lahan Industri Bekasi', 'category' => 'Lelang', 'status' => 'Available', 'location' => 'Bekasi, Jawa Barat'],
            ['title' => 'Perkebunan Holtikultura Cianjur', 'category' => 'Lelang', 'status' => 'Sold Out', 'location' => 'Cianjur, Jawa Barat'],
            ['title' => 'Tanah Pertanian Premium Karawang', 'category' => 'Lelang', 'status' => 'Available', 'location' => 'Karawang, Jawa Barat'],
        ];

        foreach ($assets as $asset) {
            Asset::create([
                'title' => $asset['title'],
                'description' => '<h2>' . $asset['title'] . '</h2><p>Properti berkualitas tinggi di lokasi strategis. Lihat detail lengkap dan hubungi agen kami untuk informasi lebih lanjut.</p>',
                'category' => $asset['category'],
                'status' => $asset['status'],
                'location' => $asset['location'],
                'photos' => [],
            ]);
        }
    }
}
