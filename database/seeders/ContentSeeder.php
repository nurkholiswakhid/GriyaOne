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
        $videos = [
            // Video Pembelajaran Basic
            ['title' => 'Cara Merawat Properti Investasi', 'desc' => 'Video panduan lengkap tentang cara merawat properti investasi agar tetap terawat dan nilai properti meningkat.', 'url' => 'https://www.youtube.com/watch?v=rfscVS0vtbw'],
            ['title' => 'Strategi Investasi Properti Pemula', 'desc' => 'Tutorial untuk pemula yang ingin memulai investasi properti dengan strategi yang tepat dan menguntungkan.', 'url' => 'https://www.youtube.com/watch?v=kqtD5dpn9C8'],
            ['title' => 'Analisis Pasar Properti 2026', 'desc' => 'Analisis mendalam tentang tren pasar properti di 2026 dan peluang investasi yang menarik.', 'url' => 'https://www.youtube.com/watch?v=bQu_QSO-2Ro'],
            ['title' => 'Cara Negosiasi Harga Properti', 'desc' => 'Tips dan trik ahli dalam bernegosiasi harga properti untuk mendapatkan deal terbaik.', 'url' => 'https://www.youtube.com/watch?v=pTB0EiLXUC8'],
            ['title' => 'Legal Due Diligence Properti', 'desc' => 'Panduan lengkap melakukan due diligence pada aspek legal sebelum transaksi properti.', 'url' => 'https://www.youtube.com/watch?v=vZKG11L7_fQ'],

            // Video Lanjutan
            ['title' => 'Teknik Flipping Properti Menguntungkan', 'desc' => 'Strategi membeli properti, renovasi, dan menjual kembali dengan profit maksimal.', 'url' => 'https://www.youtube.com/watch?v=zJYe8cXK3V8'],
            ['title' => 'Mengukur ROI Properti Dengan Tepat', 'desc' => 'Cara menghitung return on investment properti secara akurat untuk mengambil keputusan investasi.', 'url' => 'https://www.youtube.com/watch?v=u_2I2gu5hqg'],
            ['title' => 'Pembiayaan Properti - Bank vs Developer', 'desc' => 'Perbandingan system pembiayaan properti antara bank dan cicilan langsung ke developer.', 'url' => 'https://www.youtube.com/watch?v=RF7xS26gm_s'],
            ['title' => 'Menghindari Penipuan Jual Beli Properti', 'desc' => 'Tips penting untuk mengenali dan menghindari penipuan dalam transaksi jual beli properti.', 'url' => 'https://www.youtube.com/watch?v=EJ-LQn5j0rI'],
            ['title' => 'Diversifikasi Portfolio Properti', 'desc' => 'Strategi mendiversifikasi investasi properti di berbagai tipe dan lokasi untuk meminimalkan risiko.', 'url' => 'https://www.youtube.com/watch?v=_deFyJQhQB8'],

            // Webinar & Expert Talk
            ['title' => 'Webinar: Tren Properti Jakarta 2026', 'desc' => 'Webinar spesial dengan expert properti membahas trend dan prediksi pasar Jakarta tahun 2026.', 'url' => 'https://www.youtube.com/watch?v=V7Mq0oIy1gI'],
            ['title' => 'Expert Talk: Properti Syariah', 'desc' => 'Diskusi mendalam tentang investasi properti sesuai prinsip syariah dan keuntungannya.', 'url' => 'https://www.youtube.com/watch?v=9gYQcLM0qEU'],
            ['title' => 'Q&A Session: Pertanyaan Investasi Properti', 'desc' => 'Sesi tanya jawab dengan ahli properti menjawab pertanyaan dari members tentang investasi properti.', 'url' => 'https://www.youtube.com/watch?v=yQYHCNk4Wz0'],
            ['title' => 'Success Story: Dari Nol ke Hero Property', 'desc' => 'Kisah nyata dari investor properti yang berhasil membangun portofolio properti jutaan dollar.', 'url' => 'https://www.youtube.com/watch?v=6rvWFJfN3Qi'],
            ['title' => 'Technology in Real Estate 2026', 'desc' => 'Perkembangan teknologi dalam industri properti dan bagaimana memanfaatkannya untuk kesuksesan.', 'url' => 'https://www.youtube.com/watch?v=r-K0BPjlN1A'],
        ];

        foreach ($videos as $video) {
            Content::create([
                'title' => $video['title'],
                'description' => $video['desc'],
                'file_path' => $video['url'],
                'is_published' => true,
            ]);
        }
    }
}
