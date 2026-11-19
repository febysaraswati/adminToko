<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $primaryKey = 'book_id';

    protected $fillable = [
        'title',
        'author',
        'year',
        'stock',
    ];

    // Relasi: Satu buku bisa memiliki banyak riwayat peminjaman
    public function loans()
    {
        return $this->hasMany(BookLoan::class, 'book_id', 'book_id');
    }
}