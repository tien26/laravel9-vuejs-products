<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

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
