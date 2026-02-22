<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed default login page settings
        $defaults = [
            ['key' => 'login_badge',           'value' => 'Platform Terpercaya'],
            ['key' => 'login_heading',         'value' => 'Kelola Properti'],
            ['key' => 'login_heading_highlight','value' => 'Lebih Mudah'],
            ['key' => 'login_description',     'value' => 'Sistem manajemen properti terintegrasi untuk tim Anda — listing aset, materi pelatihan, dan informasi terkini dalam satu platform.'],
            ['key' => 'login_feature_1_title', 'value' => 'Listing Aset Properti'],
            ['key' => 'login_feature_1_desc',  'value' => 'Kelola semua listing secara real-time'],
            ['key' => 'login_feature_2_title', 'value' => 'Multi-Role Dashboard'],
            ['key' => 'login_feature_2_desc',  'value' => 'Admin, Marketing, dan User dalam satu sistem'],
            ['key' => 'login_feature_3_title', 'value' => 'Laporan & Notifikasi'],
            ['key' => 'login_feature_3_desc',  'value' => 'Pantau aktivitas dan info terbaru'],
            ['key' => 'login_welcome',         'value' => 'Selamat Datang'],
            ['key' => 'login_welcome_sub',     'value' => 'Masuk ke akun GriyaOne Anda'],
            ['key' => 'login_app_tagline',     'value' => 'Sistem Manajemen Properti'],
        ];

        foreach ($defaults as $setting) {
            DB::table('settings')->insert(array_merge($setting, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
