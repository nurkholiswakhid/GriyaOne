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
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['price', 'year', 'property_type', 'area']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->decimal('price', 15, 2)->nullable();
            $table->integer('year')->nullable();
            $table->string('property_type')->nullable();
            $table->decimal('area', 10, 2)->nullable();
        });
    }
};
