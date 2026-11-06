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
        Schema::create('aduans', function (Blueprint $table) {
            $table->id('aduan_id'); // Mengubah ID utama menjadi aduan_id
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke tabel users (user_id)
            $table->string('jenis_aduan'); // Kolom jenis aduan
            $table->enum('prioritas', ['High', 'Middle', 'Low']); // Pilihan prioritas: High, Middle, Low
            $table->string('aduan'); // ringkasan aduan
            $table->text('teks'); // Isi teks detail aduan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aduans');
    }
};