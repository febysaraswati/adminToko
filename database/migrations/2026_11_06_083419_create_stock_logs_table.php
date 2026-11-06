<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id'); // Menyimpan ID produk tanpa foreign key constraint
            $table->enum('type', ['in', 'out']); // 'in' (masuk), 'out' (keluar)
            $table->integer('quantity'); 
            $table->text('description')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_logs');
    }
};