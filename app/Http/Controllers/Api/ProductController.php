<?php 
 
namespace App\Http\Controllers\Api; 
 
use App\Http\Controllers\Controller; 
use App\Http\Resources\ProductResource; 
use App\Models\Product; 
use Illuminate\Http\Request; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

 
class ProductController extends Controller 
{ 
    /** 
     * Display a listing of the resource. 
     */ 
    public function index(Request $request) 
    { 
        // Mendukung filter keyword dan category_id sesuai modul 4.3.4
        $query = Product::with('category');

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $limit = $request->input('limit', 10);
        $products = $query->paginate($limit); 

        return ProductResource::collection($products);            
    } 
 
    /** 
     * Store a newly created resource in storage. 
     */ 
    public function store(Request $request) 
    { 
        // 1. Validasi request (img diubah menjadi image file)
        $request->validate([ 
            'name' => 'required|string|max:150', 
            'description' => 'nullable|string', 
            'category_id' => 'required|exists:categories,category_id', 
            'price' => 'required|numeric', 
            'stock' => 'required|integer', 
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // <-- Diperbaiki di sini
        ]); 

        $imgPath = null;

        // 2. Proses upload file gambar jika ada
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Menyimpan ke storage/app/public/products (jalankan php artisan storage:link jika belum)
            $imgPath = $file->storeAs('products', $filename, 'public');
            // Ubah path agar bisa diakses publik (misal: storage/products/namafile.png)
            $imgPath = '/storage/' . $imgPath;
        }

        // 3. Create the product 
        $product = Product::create([ 
            'name' => $request->name, 
            'description' => $request->description, 
            'category_id' => $request->category_id, 
            'price' => $request->price, 
            'stock' => $request->stock, 
            'img' => $imgPath, 
            'slug' => Str::slug($request->name) // Generate slug from name 
        ]); 
 
        return new ProductResource($product); 
    } 
 
    /** 
     * Display the specified resource. 
     */ 
    public function show(string $id) 
    { 
        // Get product by ID with its category 
        $product = Product::with('category')->findOrFail($id); 
        return new ProductResource($product); 
    } 
 
    /** 
     * Update the specified resource in storage. 
     */ 
    public function update(Request $request, string $id) 
    { 
        // Find the product 
        $product = Product::findOrFail($id); 

        // 1. Validasi request
        $request->validate([ 
            'name' => 'required|string|max:150', 
            'description' => 'nullable|string', 
            'category_id' => 'required|exists:categories,category_id', 
            'price' => 'required|numeric', 
            'stock' => 'required|integer', 
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // <-- Diperbaiki di sini
        ]); 

        $imgPath = $product->img; // Pertahankan gambar lama by default

        // 2. Proses upload gambar baru jika user mengunggah file baru
        if ($request->hasFile('img')) {
            // Hapus gambar lama jika ada
            if ($product->img) {
                $oldPath = str_replace('/storage/', '', $product->img);
                Storage::disk('public')->delete($oldPath);
            }

            $file = $request->file('img');
            $filename = time() . '_' . $file->getClientOriginalName();
            $imgPath = $file->storeAs('products', $filename, 'public');
            $imgPath = '/storage/' . $imgPath;
        }

        // 3. Update the product 
        $product->update([ 
            'name' => $request->name, 
            'description' => $request->description, 
            'category_id' => $request->category_id, 
            'price' => $request->price, 
            'stock' => $request->stock, 
            'img' => $imgPath, 
            'slug' => Str::slug($request->name) // Generate slug from name 
        ]); 
 
        return new ProductResource($product); 
    } 
 
    /** 
     * Remove the specified resource from storage. 
     */ 
    public function destroy(string $id) 
    { 
        // Find and delete the product 
        $product = Product::find($id); 
        if($product != null) { 
            // Hapus file gambar dari storage jika ada
            if ($product->img) {
                $oldPath = str_replace('/storage/', '', $product->img);
                Storage::disk('public')->delete($oldPath);
            }

            $product->delete(); 
            return response()->json(['message' => 'Product deleted successfully']); 
        } 
        
        // Return a not found response 
        return response()->json(['message' => 'Product not found'], 404); 
    } 
}