<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductExportController extends Controller
{
    public function export(Request $request)
    {
        // $categoryId = $request->input('category');

        // Jika tidak ada kategori yang dipilih, export semua produk
        $products = Product::with('category')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%$search%");
            })
            ->when($request->category, function ($query, $category) {
                $query->whereHas('category', function ($q) use ($category) {
                    $q->where('name', 'like', "%$category%");
                });
            })
            ->get();
        // $query = Product::query();
        // if ($categoryId) {
        //     $query->where('category_id', $categoryId);
        // }
        // $products = $query->get();

        return Excel::download(new ProductsExport($products), 'products.xlsx');
    }
}
