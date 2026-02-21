<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('information', function (Blueprint $table) {
            $table->dropColumn('category');
            $table->foreignId('category_id')->nullable()->constrained('information_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('information', function (Blueprint $table) {
            $table->dropForeignIdFor('information_categories');
            $table->dropColumn('category_id');
            $table->string('category')->default('General');
        });
    }
};
