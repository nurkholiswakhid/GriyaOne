<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $defaults = [
            ['key' => 'login_logo_path', 'value' => '',           'created_at' => now(), 'updated_at' => now()],
            ['key' => 'login_wa_number', 'value' => '',           'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($defaults as $row) {
            DB::table('settings')->insertOrIgnore($row);
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', ['login_logo_path', 'login_wa_number'])->delete();
    }
};
