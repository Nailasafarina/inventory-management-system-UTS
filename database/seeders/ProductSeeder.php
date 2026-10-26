<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kopi = Category::where('name', 'Kopi')->first();
        $minumanDingin = Category::where('name', 'Minuman Dingin')->first();
        $cemilanRingan = Category::where('name', 'Cemilan Ringan')->first();
        $cemilanManis = Category::where('name', 'Cemilan Manis')->first();

        $products = [
            // Kategori: Kopi
            [
                'category_id' => $kopi->id,
                'sku' => 'CM-KPI-001',
                'name' => 'Es Kopi Susu Gula Aren',
                'purchase_price' => 11000,
                'selling_price' => 18000,
                'stock' => 35,
                'minimum_stock' => 8,
            ],
            [
                'category_id' => $kopi->id,
                'sku' => 'CM-KPI-002',
                'name' => 'Kopi Arabika Gayo Espresso 250gr',
                'purchase_price' => 45000,
                'selling_price' => 65000,
                'stock' => 20,
                'minimum_stock' => 5,
            ],
            [
                'category_id' => $kopi->id,
                'sku' => 'CM-KPI-003',
                'name' => 'Kopi Tubruk Robusta Dampit 250gr',
                'purchase_price' => 22000,
                'selling_price' => 32000,
                'stock' => 3, // Peringatan Stok Menipis (Stok <= Min)
                'minimum_stock' => 6,
            ],

            // Kategori: Minuman Dingin
            [
                'category_id' => $minumanDingin->id,
                'sku' => 'CM-MDR-001',
                'name' => 'Es Teh Melati Solo Jumbo',
                'purchase_price' => 4000,
                'selling_price' => 8000,
                'stock' => 60,
                'minimum_stock' => 15,
            ],
            [
                'category_id' => $minumanDingin->id,
                'sku' => 'CM-MDR-002',
                'name' => 'Matcha Latte Ice Jelly 500ml',
                'purchase_price' => 14000,
                'selling_price' => 22000,
                'stock' => 40,
                'minimum_stock' => 10,
            ],
            [
                'category_id' => $minumanDingin->id,
                'sku' => 'CM-MDR-003',
                'name' => 'Thai Tea Creamy Brown Sugar',
                'purchase_price' => 9000,
                'selling_price' => 16000,
                'stock' => 2, // Peringatan Stok Menipis
                'minimum_stock' => 8,
            ],

            // Kategori: Cemilan Ringan
            [
                'category_id' => $cemilanRingan->id,
                'sku' => 'CM-SNK-001',
                'name' => 'Keripik Kaca Mpok Nur 200gr',
                'purchase_price' => 9000,
                'selling_price' => 15000,
                'stock' => 50,
                'minimum_stock' => 10,
            ],
            [
                'category_id' => $cemilanRingan->id,
                'sku' => 'CM-SNK-002',
                'name' => 'Basreng Pedas Daun Jeruk 150gr',
                'purchase_price' => 10000,
                'selling_price' => 16000,
                'stock' => 30,
                'minimum_stock' => 8,
            ],
            [
                'category_id' => $cemilanRingan->id,
                'sku' => 'CM-SNK-003',
                'name' => 'Makaroni Pedas Si Mbok Level 3',
                'purchase_price' => 7000,
                'selling_price' => 12000,
                'stock' => 4, // Peringatan Stok Menipis
                'minimum_stock' => 10,
            ],
            [
                'category_id' => $cemilanRingan->id,
                'sku' => 'CM-SNK-004',
                'name' => 'Pangsit Goreng Balado Renyah 150gr',
                'purchase_price' => 8500,
                'selling_price' => 14000,
                'stock' => 25,
                'minimum_stock' => 6,
            ],

            // Kategori: Cemilan Manis
            [
                'category_id' => $cemilanManis->id,
                'sku' => 'CM-MNS-001',
                'name' => 'Keripik Pisang Lumer Cokelat 200gr',
                'purchase_price' => 14000,
                'selling_price' => 22000,
                'stock' => 22,
                'minimum_stock' => 5,
            ],
            [
                'category_id' => $cemilanManis->id,
                'sku' => 'CM-MNS-002',
                'name' => 'Brownies Panggang Fudgy Amanda 200gr',
                'purchase_price' => 20000,
                'selling_price' => 30000,
                'stock' => 0, // Stok Habis (Uji Validasi)
                'minimum_stock' => 5,
            ],
        ];

        foreach ($products as $prod) {
            Product::firstOrCreate(
                ['sku' => $prod['sku']],
                $prod
            );
        }
    }
}
