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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('category', ['Bank Cessie', 'AYDA', 'Lelang']);
            $table->enum('status', ['Available', 'Sold Out'])->default('Available');
            $table->decimal('price', 15, 2)->nullable();
            $table->string('location')->nullable();
            $table->integer('year')->nullable();
            $table->string('property_type')->nullable();
            $table->decimal('area', 10, 2)->nullable();
            $table->json('photos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
