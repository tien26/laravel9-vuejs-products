<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function apiIndex(Request $request)
    {
        $products = Product::with('category')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%$search%");
            })
            ->when($request->category, function ($query, $category) {
                $query->whereHas('category', function ($q) use ($category) {
                    $q->where('name', 'like', "%$category%");
                });
            })
            ->paginate(10);

        return response()->json($products);
    }

    public function getCategories(Request $request)
    {
        $categories = Category::all(); // Ambil semua kategori
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name', // Nama produk harus unik
            'category_id' => 'required|integer',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'product-' . time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('products', $imageName, 'public');
        }

        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'price_buy' => ($request->price + ($request->price * 0.3)),
            'stock' => $request->stock,
            'image' => $imagePath,
        ]);

        return response()->json(['message' => 'Produk berhasil ditambahkan', 'product' => $product], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'id tidak ditemukan' . $id]);
        }

        $request->validate([
            'name' => 'nullable|string|max:255|unique:products,name,' . $product->id, // Unik kecuali produk ini
            'category_id' => 'nullable|integer',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $image = $request->file('image');
            $imageName = 'product-' . time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('products', $imageName, 'public');

            // Update nama file ke database
            $product->image = $imagePath;
        }
        $input = $request->only(['name', 'category_id', 'price', 'price_buy', 'stock']);
        $input['price_buy'] = $request->price + ($request->price * 0.3);

        $product->update($input);

        return response()->json(['message' => 'Produk berhasil diperbarui', 'product' => $product]);
    }



    public function destroy($productId)
    {
        // Mencari produk berdasarkan ID
        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Menghapus produk
        $product->delete();

        // Mengembalikan respons sukses
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
