<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aduan;
use Illuminate\Support\Facades\Auth;

class AduanController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi input data aduan
        $request->validate([
            'jenis_aduan' => 'required|string',
            'prioritas'   => 'required|in:High,Middle,High', // atau High, Middle, Low (pastikan sesuai)
            'aduan'       => 'required|string|max:255',
            'teks'        => 'required|string',
        ]);

        // 2. Tentukan user_id (Gunakan Auth::id() jika login, atau angka 1 untuk testing Postman)
        $userId = Auth::id() ? Auth::id() : 1; 

        // 3. Simpan data ke database tabel aduans
        $aduan = Aduan::create([
            'user_id'     => $userId,
            'jenis_aduan' => $request->jenis_aduan,
            'prioritas'   => $request->prioritas,
            'aduan'       => $request->aduan,
            'teks'        => $request->teks,
        ]);

        // 4. Berikan respons (bisa JSON untuk Postman atau redirect untuk web)
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Aduan berhasil dikirim!',
                'data'    => $aduan
            ], 201);
        }

        return redirect()->back()->with('success', 'Aduan berhasil dikirim!');
    }
}