<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LibraryController extends Controller
{
    // --- MANAJEMEN BUKU (BOOKS) ---

    // Menampilkan daftar semua buku (Mendukung Pencarian & Pagination)
    public function getBooks(Request $request)
    {
        $keyword = $request->query('keyword');
        $limit = $request->query('limit', 4); // Batas default 4 data per halaman

        $query = Book::latest();

        // Logika Filter Pencarian berdasarkan Judul atau Penulis
        if (!empty($keyword)) {
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('author', 'like', "%{$keyword}%");
            });
        }

        $books = $query->paginate($limit);

        return response()->json([
            'success' => true, 
            'data' => $books
        ], 200);
    }

    // Menambah buku baru (Pendaftaran Buku + Upload Cover)
    public function storeBook(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'year' => 'required|integer',
            'stock' => 'required|integer|min:0',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/covers'), $filename);
            $coverPath = 'storage/covers/' . $filename;
        }

        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'year' => $request->year,
            'stock' => $request->stock,
            'cover' => $coverPath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Buku baru berhasil didaftarkan!',
            'data' => $book
        ], 201);
    }

    // Memperbarui data buku (Edit Buku + Update Cover)
    public function updateBook(Request $request, $id)
    {
        $book = Book::where('book_id', $id)->first();
        if (!$book) {
            return response()->json(['message' => 'Buku tidak ditemukan!'], 404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'year' => 'required|integer',
            'stock' => 'required|integer|min:0',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            // Hapus file cover lama jika ada
            if ($book->cover && file_exists(public_path($book->cover))) {
                File::delete(public_path($book->cover));
            }

            $file = $request->file('cover');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/covers'), $filename);
            $book->cover = 'storage/covers/' . $filename;
        }

        $book->title = $request->title;
        $book->author = $request->author;
        $book->year = $request->year;
        $book->stock = $request->stock;
        $book->save();

        return response()->json([
            'success' => true,
            'message' => 'Data buku berhasil diperbarui!',
            'data' => $book
        ], 200);
    }

    // Menghapus buku (Delete Buku + Hapus File Cover)
    public function destroyBook($id)
    {
        $book = Book::where('book_id', $id)->first();
        if (!$book) {
            return response()->json(['message' => 'Buku tidak ditemukan!'], 404);
        }

        // Hapus file cover jika ada
        if ($book->cover && file_exists(public_path($book->cover))) {
            File::delete(public_path($book->cover));
        }

        $book->delete();

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dihapus!'
        ], 200);
    }

    // --- MANAJEMEN PEMINJAMAN (BOOK LOANS) ---

    // Menampilkan riwayat peminjaman buku
    public function getLoans()
    {
        $loans = BookLoan::with('book')->latest()->get();
        return response()->json([
            'success' => true, 
            'data' => $loans
        ], 200);
    }

    // Mencatat peminjaman buku & mengurangi stok
    public function storeLoan(Request $request)
    {
        $request->validate([
            'book_id' => 'required',
            'borrower_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
        ]);

        $book = Book::where('book_id', $request->book_id)->first();

        if (!$book) {
            return response()->json(['message' => 'Buku tidak ditemukan!'], 404);
        }

        if ($book->stock < $request->quantity) {
            return response()->json(['message' => 'Stok buku tidak mencukupi untuk dipinjam!'], 400);
        }

        // Kurangi stok buku
        $book->stock -= $request->quantity;
        $book->save();

        // Simpan data peminjaman
        $loan = BookLoan::create([
            'book_id' => $request->book_id,
            'borrower_name' => $request->borrower_name,
            'quantity' => $request->quantity,
            'status' => 'borrowed',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman buku berhasil dicatat!',
            'data' => $loan
        ], 201);
    }
}