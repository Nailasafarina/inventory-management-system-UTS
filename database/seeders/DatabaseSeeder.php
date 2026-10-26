<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. Akun Pengguna Demo
        $admin = User::firstOrCreate(
            ['email' => 'admin@inventpro.com'],
            [
                'name' => 'Administrator Invent Pro',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $adminExample = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator Demo',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $staff = User::firstOrCreate(
            ['email' => 'staff@inventpro.com'],
            [
                'name' => 'Staff Invent Pro',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]
        );

        $staffExample = User::firstOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Staff Gudang & Kasir',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]
        );

        $customer = User::firstOrCreate(
            ['email' => 'customer@inventpro.com'],
            [
                'name' => 'Naila Safarina',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );

        $customerExample = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Naila Safarina',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );

        // 2. Eksekusi Sub-Seeder
        $this->call([
            CategorySeeder::class,
            SupplierSeeder::class,
            ProductSeeder::class,
        ]);

        // 3. Ambil Referensi Produk untuk Sample Transaksi
        $supplierKopi = Supplier::where('name', 'CV Kopi Nusantara Mandiri')->first() ?? Supplier::first();
        $supplierSnack = Supplier::where('name', 'UD Cemilan Berkah Nusantara')->first() ?? Supplier::first();

        $prodKopiSusu = Product::where('sku', 'CM-KPI-001')->first() ?? Product::first();
        $prodKeripikKaca = Product::where('sku', 'CM-SNK-001')->first() ?? Product::first();
        $prodBasreng = Product::where('sku', 'CM-SNK-002')->first() ?? Product::first();
        $prodPisangCokelat = Product::where('sku', 'CM-MNS-001')->first() ?? Product::first();
        $prodEsTeh = Product::where('sku', 'CM-MDR-001')->first() ?? Product::first();

        // 4. Sample Pembelian Stok Masuk Awal (PO)
        $purchase = Purchase::firstOrCreate(
            ['purchase_number' => 'PO-' . date('Ymd') . '-0001'],
            [
                'supplier_id' => $supplierSnack->id,
                'user_id' => $staff->id,
                'total' => 690000,
                'status' => 'completed',
                'purchased_at' => now()->subDays(2),
                'notes' => 'Penerimaan stok awal keripik kaca mpok nur & basreng pedas',
            ]
        );

        if ($prodKeripikKaca && $prodBasreng) {
            PurchaseItem::firstOrCreate(
                [
                    'purchase_id' => $purchase->id,
                    'product_id' => $prodKeripikKaca->id,
                ],
                [
                    'quantity' => 40,
                    'unit_cost' => $prodKeripikKaca->purchase_price,
                    'subtotal' => 40 * $prodKeripikKaca->purchase_price,
                ]
            );

            PurchaseItem::firstOrCreate(
                [
                    'purchase_id' => $purchase->id,
                    'product_id' => $prodBasreng->id,
                ],
                [
                    'quantity' => 30,
                    'unit_cost' => $prodBasreng->purchase_price,
                    'subtotal' => 30 * $prodBasreng->purchase_price,
                ]
            );
        }

        // 5. Sample Penjualan Kasir (Takeaway)
        $saleRetail = Sale::firstOrCreate(
            ['sale_number' => 'INV-' . date('Ymd') . '-0001'],
            [
                'user_id' => $staff->id,
                'total' => 70000,
                'status' => 'completed',
                'sold_at' => now()->subDay(),
                'notes' => 'Pesanan takeaway es kopi susu gula aren & keripik kaca',
            ]
        );

        if ($prodKopiSusu && $prodKeripikKaca && $prodBasreng) {
            SaleItem::firstOrCreate(
                [
                    'sale_id' => $saleRetail->id,
                    'product_id' => $prodKopiSusu->id,
                ],
                [
                    'quantity' => 2,
                    'unit_price' => $prodKopiSusu->selling_price,
                    'subtotal' => 2 * $prodKopiSusu->selling_price,
                ]
            );

            SaleItem::firstOrCreate(
                [
                    'sale_id' => $saleRetail->id,
                    'product_id' => $prodKeripikKaca->id,
                ],
                [
                    'quantity' => 1,
                    'unit_price' => $prodKeripikKaca->selling_price,
                    'subtotal' => 1 * $prodKeripikKaca->selling_price,
                ]
            );

            SaleItem::firstOrCreate(
                [
                    'sale_id' => $saleRetail->id,
                    'product_id' => $prodBasreng->id,
                ],
                [
                    'quantity' => 1,
                    'unit_price' => $prodBasreng->selling_price,
                    'subtotal' => 1 * $prodBasreng->selling_price,
                ]
            );
        }

        // 6. Sample Pesanan Pelanggan (Naila Safarina)
        $saleCustomer1 = Sale::firstOrCreate(
            ['sale_number' => 'INV-' . date('Ymd') . '-0002'],
            [
                'user_id' => $staff->id,
                'customer_id' => $customer->id,
                'total' => 52000,
                'status' => 'completed',
                'sold_at' => now()->subHours(4),
                'notes' => 'Pesanan es kopi susu gula aren & keripik kaca mpok nur',
            ]
        );

        if ($prodKopiSusu && $prodKeripikKaca) {
            SaleItem::firstOrCreate(
                [
                    'sale_id' => $saleCustomer1->id,
                    'product_id' => $prodKopiSusu->id,
                ],
                [
                    'quantity' => 2,
                    'unit_price' => $prodKopiSusu->selling_price,
                    'subtotal' => 2 * $prodKopiSusu->selling_price,
                ]
            );

            SaleItem::firstOrCreate(
                [
                    'sale_id' => $saleCustomer1->id,
                    'product_id' => $prodKeripikKaca->id,
                ],
                [
                    'quantity' => 1,
                    'unit_price' => $prodKeripikKaca->selling_price,
                    'subtotal' => 1 * $prodKeripikKaca->selling_price,
                ]
            );
        }

        $saleCustomer2 = Sale::firstOrCreate(
            ['sale_number' => 'INV-' . date('Ymd') . '-0003'],
            [
                'user_id' => $customer->id,
                'customer_id' => $customer->id,
                'total' => 52000,
                'status' => 'completed',
                'sold_at' => now()->subHour(),
                'notes' => 'Pesanan keripik pisang cokelat lumer & es teh melati jumbo',
            ]
        );

        if ($prodPisangCokelat && $prodEsTeh && $prodKeripikKaca) {
            SaleItem::firstOrCreate(
                [
                    'sale_id' => $saleCustomer2->id,
                    'product_id' => $prodPisangCokelat->id,
                ],
                [
                    'quantity' => 1,
                    'unit_price' => $prodPisangCokelat->selling_price,
                    'subtotal' => 1 * $prodPisangCokelat->selling_price,
                ]
            );

            SaleItem::firstOrCreate(
                [
                    'sale_id' => $saleCustomer2->id,
                    'product_id' => $prodKeripikKaca->id,
                ],
                [
                    'quantity' => 2,
                    'unit_price' => $prodKeripikKaca->selling_price,
                    'subtotal' => 2 * $prodKeripikKaca->selling_price,
                ]
            );
        }
    }
}
