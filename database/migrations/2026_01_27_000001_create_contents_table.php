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
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['Video', 'Materi', 'Info']); // Video pembelajaran, File materi, Info terbaru
            $table->enum('category', ['Training', 'Challenge', 'Bonus'])->nullable();
            $table->string('thumbnail')->nullable(); // untuk preview video/materi
            $table->string('file_path')->nullable(); // path file materi atau link video
            $table->integer('duration')->nullable(); // durasi video dalam detik
            $table->boolean('is_published')->default(true);
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
