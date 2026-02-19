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
        Asset::create([
            'title' => 'Rumah Mewah di Jakarta Selatan',
            'description' => '<h2>Deskripsi Properti</h2><p>Rumah mewah berlokasi di area premium Jakarta Selatan dengan akses mudah ke pusat bisnis dan fasilitas umum.</p><p><strong>Fitur Utama:</strong></p><ul><li>Luas bangunan: 500 m²</li><li>Luas tanah: 800 m²</li><li>4 kamar tidur + 3 kamar mandi</li><li>Garasi mobil 2 unit</li><li>Halaman luas dengan kolam renang</li><li>Sistem keamanan 24 jam</li></ul><p>Dibangun dengan arsitektur modern dan dilengkapi dengan semua amenities lengkap.</p>',
            'category' => 'Bank Cessie',
            'status' => 'Available',
            'location' => 'Jakarta Selatan, DKI Jakarta',
            'photos' => [],
        ]);

        Asset::create([
            'title' => 'Apartemen Modern di Pusat Kota',
            'description' => '<h2>Apartemen Strategis</h2><p>Apartemen strategis dengan view kota yang menakjubkan.</p><p><strong>Spesifikasi:</strong></p><ul><li>Tipe: Studio & 2 Bedroom</li><li>Lantai: 15 - 28</li><li>View: Kota, Taman Kota</li><li>Fasilitas gym, kolam renang, taman bermain</li><li>Security 24 jam dengan CCTV</li></ul><p>Lokasi dekat dengan stasiun MRT, mall, dan pusat perbelanjaan. Unit dilengkapi dengan fasilitas premium dan fully furnished.</p>',
            'category' => 'AYDA',
            'status' => 'Available',
            'location' => 'Jakarta Pusat, DKI Jakarta',
            'photos' => [],
        ]);

        Asset::create([
            'title' => 'Tanah Komersial Lokasi Strategis',
            'description' => '<h2>Lahan Komersial Premium</h2><p>Tanah komersial dengan lokasi yang sangat strategis untuk bisnis retail, kantor, atau pengembangan properti.</p><p><strong>Informasi Tanah:</strong></p><ul><li>Luas: 5000 m²</li><li>Legalitas: SHM (Sertifikat Hak Milik)</li><li>Akses jalan: Jalan utama</li><li>Zoning: Komersial</li><li>Infrastruktur lengkap</li></ul><p>Area dengan lalu lintas tinggi dan visibility sempurna untuk bisnis Anda.</p>',
            'category' => 'Lelang',
            'status' => 'Available',
            'location' => 'Bandung, Jawa Barat',
            'photos' => [],
        ]);

        Asset::create([
            'title' => 'Rumah Cluster di Kawasan Berkembang',
            'description' => '<h2>Rumah Cluster Modern</h2><p>Rumah cluster dengan desain minimalis modern di kawasan yang terus berkembang.</p><p><strong>Fasilitas Lengkap:</strong></p><ul><li>Keamanan 24 jam dengan gate system</li><li>Taman bermain anak</li><li>Jogging track</li><li>Area komersial (mini market, warung kopi)</li><li>Mushola</li><li>Tempat parkir tamu</li></ul><p>Lokasi strategis dengan akses mudah ke sekolah, rumah sakit, dan pusat perbelanjaan.</p>',
            'category' => 'Bank Cessie',
            'status' => 'Sold Out',
            'location' => 'Depok, Jawa Barat',
            'photos' => [],
        ]);

        Asset::create([
            'title' => 'Ruang Perkantoran di Gedung Komersial',
            'description' => '<h2>Office Space Premium</h2><p>Office space di gedung komersial kelas A dengan fasilitas modern dan infrastruktur terbaik.</p><p><strong>Fasilitas Perkantoran:</strong></p><ul><li>Luas unit: 100 - 1000 m²</li><li>Lantai berlangganan</li><li>Parking ratio 1:4</li><li>High speed internet</li><li>Meeting room</li><li>Reception area</li><li>Cafeteria & restaurant</li></ul><p>Lokasi di jantung CBD Jakarta dengan akses mudah ke berbagai transportasi umum.</p>',
            'category' => 'AYDA',
            'status' => 'Available',
            'location' => 'Jakarta Barat, DKI Jakarta',
            'photos' => [],
        ]);
    }
}
