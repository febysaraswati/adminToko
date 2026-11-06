<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aduan extends Model
{
    use HasFactory;

    protected $table = 'aduans';
    protected $primaryKey = 'aduan_id';

    protected $fillable = [
        'user_id',
        'jenis_aduan',
        'prioritas',
        'aduan',
        'teks',
    ];

    // Relasi: Setiap aduan dimiliki oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}