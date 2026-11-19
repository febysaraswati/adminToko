<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_loans', function (Blueprint $table) {
            $table->id('loan_id'); // Primary Key transaksi peminjaman
            $table->unsignedBigInteger('book_id'); // Relasi ke tabel books
            $table->string('borrower_name'); // Nama peminjam
            $table->integer('quantity'); // Jumlah buku yang dipinjam
            $table->enum('status', ['borrowed', 'returned'])->default('borrowed'); // Status: Dipinjam / Dikembalikan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_loans');
    }
};