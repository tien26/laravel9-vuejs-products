<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tambahkan data produk
        DB::table('products')->insert([
            [
                'name' => 'Smartphone Samsung Galaxy',
                'category_id' => 1, // Elektronik
                'price' => 5000000,
                'price_buy' => 4500000
            ],
            [
                'name' => 'Sofa Kulit',
                'category_id' => 2, // Furnitur
                'price' => 2000000,
                'price_buy' => 1800000
            ],
            [
                'name' => 'Kaos Polos',
                'category_id' => 3, // Pakaian
                'price' => 150000,
                'price_buy' => 100000
            ],
            [
                'name' => 'Nasi Goreng Spesial',
                'category_id' => 4, // Makanan
                'price' => 30000,
                'price_buy' => 20000
            ],
            [
                'name' => 'Buku Pemrograman Laravel',
                'category_id' => 5, // Buku
                'price' => 150000,
                'price_buy' => 100000
            ],
            [
                'name' => 'Smartphone Samsung Galaxy 1',
                'category_id' => 1, // Elektronik
                'price' => 5000000,
                'price_buy' => 4500000
            ],
            [
                'name' => 'Sofa Kulit 1',
                'category_id' => 2, // Furnitur
                'price' => 2000000,
                'price_buy' => 1800000
            ],
            [
                'name' => 'Kaos Polos 1',
                'category_id' => 3, // Pakaian
                'price' => 150000,
                'price_buy' => 100000
            ],
            [
                'name' => 'Nasi Goreng Spesial 1',
                'category_id' => 4, // Makanan
                'price' => 30000,
                'price_buy' => 20000
            ],
            [
                'name' => 'Buku Pemrograman Laravel 1',
                'category_id' => 5, // Buku
                'price' => 150000,
                'price_buy' => 100000
            ],
            [
                'name' => 'Smartphone Samsung Galaxy2',
                'category_id' => 1, // Elektronik
                'price' => 5000000,
                'price_buy' => 4500000
            ],
            [
                'name' => 'Sofa Kulit2',
                'category_id' => 2, // Furnitur
                'price' => 2000000,
                'price_buy' => 1800000
            ],
            [
                'name' => 'Kaos Polos2',
                'category_id' => 3, // Pakaian
                'price' => 150000,
                'price_buy' => 100000
            ],
            [
                'name' => 'Nasi Goreng Spesial2',
                'category_id' => 4, // Makanan
                'price' => 30000,
                'price_buy' => 20000
            ],
            [
                'name' => 'Buku Pemrograman Laravel2',
                'category_id' => 5, // Buku
                'price' => 150000,
                'price_buy' => 100000
            ],
        ]);
    }
}
