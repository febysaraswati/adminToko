<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Menentukan primary key tabel adalah 'product_id'
    protected $primaryKey = 'product_id';

    // Jika tipe data primary key bukan auto-increment integer, bisa diatur public $incrementing = false; (biarkan default jika auto-increment)

    // Fields yang boleh diisi massal
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'price',
        'img',
        'stock',
        'slug'
    ];

    public function category()
    {
        // Relasi ke tabel categories
        return $this->belongsTo(Category::class, 'category_id');
    }
    
    public function stockLogs()
    {
        // Relasi ke StockLog dengan foreign key product_id
        return $this->hasMany(StockLog::class, 'product_id', 'product_id');
    }   
}