<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookLoan extends Model
{
    use HasFactory;

    protected $primaryKey = 'loan_id';

    protected $fillable = [
        'book_id',
        'borrower_name',
        'quantity',
        'status',
    ];

    // Relasi: Data peminjaman ini milik satu buku tertentu
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }
}