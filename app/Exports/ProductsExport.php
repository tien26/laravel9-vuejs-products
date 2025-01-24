<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->products;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Category',
            'Price',
            'Price Buy',
            'Stock'
        ];
    }
    // Memetakan data produk ke format yang sesuai
    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->category->name,
            $product->price,
            $product->price_buy,
            $product->stock,
        ];
    }

    // Judul sheet Excel
    public function title(): string
    {
        return 'Products';
    }
}
