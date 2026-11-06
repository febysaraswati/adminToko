<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StockLog;
use App\Models\Product;
use Illuminate\Http\Request;

class StockLogController extends Controller
{
    // Menampilkan semua riwayat stok
    public function index()
    {
        $logs = StockLog::with('product')->latest()->get();
        return response()->json(['success' => true, 'data' => $logs], 200);
    }

    // Menambah riwayat stok (Barang Masuk / Keluar) dan otomatis update stok produk
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        // Mencari produk berdasarkan primary key 'product_id' secara eksplisit agar tidak mencari kolom 'id'
        $product = Product::where('product_id', $request->product_id)->first();

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan!'], 404);
        }

        // Update jumlah stok produk utama
        if ($request->type === 'in') {
            $product->stock += $request->quantity;
        } else {
            if ($product->stock < $request->quantity) {
                return response()->json(['message' => 'Stok tidak mencukupi!'], 400);
            }
            $product->stock -= $request->quantity;
        }
        $product->save();

        // Simpan ke tabel riwayat (stock_logs)
        $log = StockLog::create([
            'product_id' => $request->product_id,
            'type' => $request->type,
            'quantity' => $request->quantity,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Riwayat stok berhasil ditambahkan!',
            'data' => $log
        ], 201);
    }
}