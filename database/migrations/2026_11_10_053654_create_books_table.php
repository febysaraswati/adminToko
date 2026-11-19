<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id('book_id'); // Primary Key buku
            $table->string('title'); // Judul buku
            $table->string('author'); // Penulis buku
            $table->integer('year'); // Tahun terbit
            $table->integer('stock')->default(0); // Stok buku tersedia
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};