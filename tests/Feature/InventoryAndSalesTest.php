<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;

class InventoryAndSalesTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $staff;
    private Category $category;
    private Supplier $supplier;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->staff = User::create([
            'name' => 'Staff Test',
            'email' => 'staff@test.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);

        $this->category = Category::create([
            'name' => 'Elektronik Test',
            'description' => 'Kategori untuk unit testing',
        ]);

        $this->supplier = Supplier::create([
            'name' => 'PT Supplier Test',
            'phone' => '081234567890',
            'email' => 'supplier@test.com',
        ]);
    }

    /**
     * Skenario 1 (Slide 19): Auth
     * Guest tidak dapat membuka dashboard; password salah ditolak.
     */
    public function test_guest_cannot_access_dashboard_and_wrong_password_rejected()
    {
        // 1. Guest diarahkan ke login
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');

        // 2. Login dengan password salah ditolak
        $loginFail = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'passwordsalah',
        ]);
        $loginFail->assertSessionHasErrors('email');
        $this->assertGuest();

        // 3. Login sukses dengan password benar
        $loginSuccess = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);
        $loginSuccess->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->admin);

        // Pastikan hamburger menu sudah dihapus dari dashboard
        $dashboardResponse = $this->actingAs($this->admin)->get('/dashboard');
        $dashboardResponse->assertOk();
        $dashboardResponse->assertDontSee('id="open-sidebar-btn"', false);
    }

    /**
     * Skenario 2 (Slide 19): Product Validation
     * SKU duplikat ditolak; harga negatif ditolak.
     */
    public function test_product_duplicate_sku_and_negative_price_are_rejected()
    {
        Product::create([
            'category_id' => $this->category->id,
            'sku' => 'SKU-TEST-001',
            'name' => 'Produk Asli',
            'purchase_price' => 10000,
            'selling_price' => 15000,
            'stock' => 10,
            'minimum_stock' => 2,
        ]);

        // Coba masukkan SKU yang sama (Duplikat)
        $duplicateResponse = $this->actingAs($this->admin)->post('/products', [
            'category_id' => $this->category->id,
            'sku' => 'SKU-TEST-001',
            'name' => 'Produk Duplikat',
            'purchase_price' => 20000,
            'selling_price' => 25000,
            'stock' => 5,
            'minimum_stock' => 2,
        ]);
        $duplicateResponse->assertSessionHasErrors('sku');

        // Coba masukkan harga negatif
        $negativeResponse = $this->actingAs($this->admin)->post('/products', [
            'category_id' => $this->category->id,
            'sku' => 'SKU-TEST-002',
            'name' => 'Produk Minus',
            'purchase_price' => -5000,
            'selling_price' => -10000,
            'stock' => 5,
            'minimum_stock' => 2,
        ]);
        $negativeResponse->assertSessionHasErrors(['purchase_price', 'selling_price']);
    }

    /**
     * Skenario 2b: Edit Produk termasuk Kuantitas Stok
     */
    public function test_product_update_allows_editing_quantity_and_rejects_negative_quantity()
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'sku' => 'SKU-EDIT-001',
            'name' => 'Produk Awal',
            'purchase_price' => 10000,
            'selling_price' => 15000,
            'stock' => 10,
            'minimum_stock' => 2,
        ]);

        // Pastikan form edit menampilkan field input kuantitas stok yang bisa diedit
        $editPage = $this->actingAs($this->admin)->get("/products/{$product->id}/edit");
        $editPage->assertOk();
        $editPage->assertSee('name="stock"', false);
        $editPage->assertSee('Kuantitas / Stok', false);

        // Coba perbarui kuantitas produk menjadi 50
        $response = $this->actingAs($this->admin)->put("/products/{$product->id}", [
            'category_id' => $this->category->id,
            'sku' => 'SKU-EDIT-001',
            'name' => 'Produk Awal Diedit',
            'purchase_price' => 12000,
            'selling_price' => 18000,
            'stock' => 50,
            'minimum_stock' => 5,
        ]);

        $response->assertRedirect('/products');
        $product->refresh();
        $this->assertEquals(50, $product->stock);
        $this->assertEquals('Produk Awal Diedit', $product->name);

        // Kuantitas negatif ditolak
        $invalidResponse = $this->actingAs($this->admin)->put("/products/{$product->id}", [
            'category_id' => $this->category->id,
            'sku' => 'SKU-EDIT-001',
            'name' => 'Produk Awal Diedit',
            'purchase_price' => 12000,
            'selling_price' => 18000,
            'stock' => -5,
            'minimum_stock' => 5,
        ]);

        $invalidResponse->assertSessionHasErrors('stock');
    }

    /**
     * Skenario 2b2: Upload dan Update Gambar Produk
     */
    public function test_product_image_upload_and_update_and_validation()
    {
        Storage::fake('public');

        // 1. Validasi file bukan gambar ditolak
        $invalidFile = UploadedFile::fake()->create('document.pdf', 100);
        $badResponse = $this->actingAs($this->admin)->post('/products', [
            'category_id' => $this->category->id,
            'sku' => 'SKU-IMG-BAD',
            'name' => 'Produk Bad File',
            'purchase_price' => 10000,
            'selling_price' => 15000,
            'stock' => 5,
            'minimum_stock' => 2,
            'image' => $invalidFile,
        ]);
        $badResponse->assertSessionHasErrors('image');

        // 2. Berhasil upload gambar saat create produk
        $imageFile = UploadedFile::fake()->image('produk_baru.jpg', 400, 400);
        $response = $this->actingAs($this->admin)->post('/products', [
            'category_id' => $this->category->id,
            'sku' => 'SKU-IMG-001',
            'name' => 'Produk Bergambar',
            'purchase_price' => 10000,
            'selling_price' => 15000,
            'stock' => 5,
            'minimum_stock' => 2,
            'image' => $imageFile,
        ]);

        $response->assertRedirect('/products');
        $product = Product::where('sku', 'SKU-IMG-001')->first();
        $this->assertNotNull($product);
        $this->assertNotNull($product->image);
        Storage::disk('public')->assertExists($product->image);

        // 3. Update gambar dengan gambar baru
        $newImageFile = UploadedFile::fake()->image('produk_update.png', 400, 400);
        $oldImagePath = $product->image;

        $updateResponse = $this->actingAs($this->admin)->put("/products/{$product->id}", [
            'category_id' => $this->category->id,
            'sku' => 'SKU-IMG-001',
            'name' => 'Produk Bergambar Update',
            'purchase_price' => 10000,
            'selling_price' => 15000,
            'stock' => 5,
            'minimum_stock' => 2,
            'image' => $newImageFile,
        ]);

        $updateResponse->assertRedirect('/products');
        $product->refresh();
        Storage::disk('public')->assertExists($product->image);
        Storage::disk('public')->assertMissing($oldImagePath);
    }

    /**
     * Skenario 2c: Edit Kategori termasuk Kuantitas
     */
    public function test_category_edit_allows_editing_quantity_and_rejects_negative_quantity()
    {
        $cat = Category::create([
            'name' => 'Kategori Edit Test',
            'description' => 'Deskripsi Kategori',
            'quantity' => 15,
        ]);

        $editPage = $this->actingAs($this->admin)->get("/categories/{$cat->id}/edit");
        $editPage->assertOk();
        $editPage->assertSee('name="quantity"', false);
        $editPage->assertSee('Kuantitas / Jumlah Stok', false);

        // Update kuantitas kategori
        $response = $this->actingAs($this->admin)->put("/categories/{$cat->id}", [
            'name' => 'Kategori Edit Test Updated',
            'description' => 'Deskripsi Baru',
            'quantity' => 75,
        ]);

        $response->assertRedirect('/categories');
        $cat->refresh();
        $this->assertEquals(75, $cat->quantity);
        $this->assertEquals('Kategori Edit Test Updated', $cat->name);

        // Kuantitas negatif ditolak
        $invalidResponse = $this->actingAs($this->admin)->put("/categories/{$cat->id}", [
            'name' => 'Kategori Edit Test Updated',
            'description' => 'Deskripsi Baru',
            'quantity' => -10,
        ]);

        $invalidResponse->assertSessionHasErrors('quantity');
    }

    /**
     * Skenario 3 (Slide 19): Purchase Transaction
     * Pembelian sukses menambah stok sesuai kuantitas.
     */
    public function test_purchase_transaction_increments_product_stock_accurately()
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'sku' => 'SKU-BUY-001',
            'name' => 'Mouse Gaming',
            'purchase_price' => 50000,
            'selling_price' => 75000,
            'stock' => 10, // Stok awal = 10
            'minimum_stock' => 5,
        ]);

        $response = $this->actingAs($this->staff)->post('/purchases', [
            'supplier_id' => $this->supplier->id,
            'purchased_at' => now()->format('Y-m-d H:i:s'),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 15, // Tambah 15 unit
                    'unit_cost' => 50000,
                ],
            ],
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals(25, $product->fresh()->stock); // 10 + 15 = 25
        $this->assertDatabaseHas('purchases', [
            'total' => 750000, // 15 * 50.000
            'status' => 'completed',
        ]);
    }

    /**
     * Skenario 4 (Slide 19): Sale Transaction
     * Penjualan sukses mengurangi stok sesuai kuantitas.
     */
    public function test_sale_transaction_decrements_product_stock_accurately()
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'sku' => 'SKU-SALE-001',
            'name' => 'Keyboard Mechanical',
            'purchase_price' => 100000,
            'selling_price' => 150000,
            'stock' => 20, // Stok awal = 20
            'minimum_stock' => 5,
        ]);

        $response = $this->actingAs($this->staff)->post('/sales', [
            'sold_at' => now()->format('Y-m-d H:i:s'),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 7, // Jual 7 unit
                ],
            ],
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals(13, $product->fresh()->stock); // 20 - 7 = 13
        $this->assertDatabaseHas('sales', [
            'total' => 1050000, // 7 * 150.000
            'status' => 'completed',
        ]);
    }

    /**
     * Skenario 5 (Slide 19): Insufficient Stock
     * Penjualan dengan stok tidak cukup ditolak tanpa perubahan data.
     */
    public function test_insufficient_stock_sale_is_rejected_without_changing_data()
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'sku' => 'SKU-OUT-001',
            'name' => 'Harddisk Eksternal',
            'purchase_price' => 500000,
            'selling_price' => 700000,
            'stock' => 3, // Sisa stok hanya 3
            'minimum_stock' => 1,
        ]);

        // Coba jual 5 unit (melebihi stok 3)
        $response = $this->actingAs($this->staff)->post('/sales', [
            'sold_at' => now()->format('Y-m-d H:i:s'),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 5,
                ],
            ],
        ]);

        // Transaksi harus ditolak dengan validasi error
        $response->assertSessionHasErrors('items');

        // Pastikan stok TIDAK berubah sama sekali
        $this->assertEquals(3, $product->fresh()->stock);

        // Pastikan TIDAK ada data penjualan yang tersimpan di database
        $this->assertDatabaseMissing('sales', [
            'total' => 3500000,
        ]);
    }

    /**
     * Skenario 6 (Slide 19): Atomicity & DB::transaction Rollback
     * Jika satu item transaksi gagal, seluruh transaksi rollback secara atomik.
     */
    public function test_atomicity_and_transaction_rollback_when_one_item_fails()
    {
        $productA = Product::create([
            'category_id' => $this->category->id,
            'sku' => 'SKU-A-001',
            'name' => 'Barang A Cukup',
            'purchase_price' => 10000,
            'selling_price' => 20000,
            'stock' => 10, // Stok cukup
            'minimum_stock' => 2,
        ]);

        $productB = Product::create([
            'category_id' => $this->category->id,
            'sku' => 'SKU-B-001',
            'name' => 'Barang B Kurang',
            'purchase_price' => 30000,
            'selling_price' => 50000,
            'stock' => 2, // Stok HANYA 2
            'minimum_stock' => 1,
        ]);

        // Keranjang belanja dengan 2 item: Item A valid (jual 2), tapi Item B melebihi stok (jual 10)
        $response = $this->actingAs($this->staff)->post('/sales', [
            'sold_at' => now()->format('Y-m-d H:i:s'),
            'items' => [
                [
                    'product_id' => $productA->id,
                    'quantity' => 2,
                ],
                [
                    'product_id' => $productB->id,
                    'quantity' => 10, // Melebihi stok!
                ],
            ],
        ]);

        $response->assertSessionHasErrors('items');

        // BUKTI ATOMICITY: Stok barang A tidak boleh berkurang meskipun diproses lebih awal!
        $this->assertEquals(10, $productA->fresh()->stock);
        $this->assertEquals(2, $productB->fresh()->stock);

        // Tidak ada header sale yang tersimpan
        $this->assertEquals(0, Sale::count());
    }

    /**
     * Skenario 7 (Slide 19): Authorization / RBAC Server-Side
     * Staff tidak dapat mengakses fungsi Admin (misal: tambah produk, kelola user, kategori).
     */
    public function test_authorization_staff_cannot_access_admin_functions()
    {
        // 1. Staff mencoba membuka form create product -> 403 Forbidden
        $productCreate = $this->actingAs($this->staff)->get('/products/create');
        $productCreate->assertStatus(403);

        // 2. Staff mencoba menghapus kategori -> 403 Forbidden
        $categoryDelete = $this->actingAs($this->staff)->delete("/categories/{$this->category->id}");
        $categoryDelete->assertStatus(403);

        // 3. Staff mencoba membuka halaman users -> 403 Forbidden
        $userList = $this->actingAs($this->staff)->get('/users');
        $userList->assertStatus(403);

        // 4. Admin dapat mengakses halaman-halaman tersebut
        $adminAccess = $this->actingAs($this->admin)->get('/products/create');
        $adminAccess->assertStatus(200);
    }

    /**
     * Skenario 8 (Slide 19): Search, Filter & Pagination
     * Filter dan pagination menghasilkan data yang sesuai.
     */
    public function test_search_filter_and_pagination_work_properly()
    {
        // Buat 15 produk untuk menguji pagination (limit 10 per halaman)
        for ($i = 1; $i <= 15; $i++) {
            Product::create([
                'category_id' => $this->category->id,
                'sku' => "SKU-PAGI-" . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name' => "Kabel Data Type-C #{$i}",
                'purchase_price' => 10000,
                'selling_price' => 20000,
                'stock' => 10,
                'minimum_stock' => 2,
            ]);
        }

        // 1. Uji Pagination: Halaman 1 menghasilkan 10 data
        $page1 = $this->actingAs($this->staff)->get('/products');
        $page1->assertStatus(200);
        $page1->assertViewHas('products', function ($products) {
            return $products->count() === 10 && $products->total() === 15;
        });

        // 2. Uji Pencarian: Cari "Kabel Data Type-C #12"
        $search = $this->actingAs($this->staff)->get('/products?search=Kabel Data Type-C %2312');
        $search->assertStatus(200);
        $search->assertSee('SKU-PAGI-012');
    }

    /**
     * Skenario 9: Role Customer
     * Customer dapat melihat katalog produk dan riwayat belanjanya sendiri.
     */
    public function test_customer_redirected_to_customer_dashboard_and_can_view_catalog()
    {
        $customer = User::create([
            'name' => 'Budi Customer',
            'email' => 'budi@customer.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $product = Product::create([
            'category_id' => $this->category->id,
            'sku' => 'SKU-CUST-001',
            'name' => 'Flashdisk 64GB',
            'purchase_price' => 50000,
            'selling_price' => 80000,
            'stock' => 10,
            'minimum_stock' => 2,
        ]);

        // Login sebagai customer
        $login = $this->post('/login', [
            'email' => 'budi@customer.com',
            'password' => 'password',
        ]);
        $login->assertRedirect(route('customer.dashboard'));

        // Akses dashboard customer
        $response = $this->actingAs($customer)->get('/customer/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Flashdisk 64GB');
        $response->assertSee('Portal Pelanggan');
    }

    /**
     * Skenario 10: RBAC Proteksi Customer
     * Customer diblokir dari route Staff dan Admin.
     */
    public function test_customer_cannot_access_staff_or_admin_routes()
    {
        $customer = User::create([
            'name' => 'Ani Customer',
            'email' => 'ani@customer.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        // 1. Customer dilarang mengakses form create purchase (Stock-in) -> 403
        $this->actingAs($customer)->get('/purchases/create')->assertStatus(403);

        // 2. Customer dilarang mengakses form create master product -> 403
        $this->actingAs($customer)->get('/products/create')->assertStatus(403);

        // 3. Customer dilarang mengakses manajemen users -> 403
        $this->actingAs($customer)->get('/users')->assertStatus(403);

        // 4. Customer dilarang mengakses backend kasir sales/create -> 403
        $this->actingAs($customer)->get('/sales/create')->assertStatus(403);
    }

    /**
     * Skenario 11: Customer Checkout & Atomic Stock Decrement
     */
    public function test_customer_can_checkout_product_and_stock_decrements()
    {
        $customer = User::create([
            'name' => 'Dedi Customer',
            'email' => 'dedi@customer.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $product = Product::create([
            'category_id' => $this->category->id,
            'sku' => 'SKU-BUY-999',
            'name' => 'Speaker Bluetooth',
            'purchase_price' => 150000,
            'selling_price' => 200000,
            'stock' => 5,
            'minimum_stock' => 1,
        ]);

        $response = $this->actingAs($customer)->post('/customer/checkout', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals(3, $product->fresh()->stock); // 5 - 2 = 3
        $this->assertDatabaseHas('sales', [
            'user_id' => $customer->id,
            'customer_id' => $customer->id,
            'total' => 400000,
        ]);

        $sale = \App\Models\Sale::where('user_id', $customer->id)->latest()->first();
        $invoiceResponse = $this->actingAs($customer)->get(route('customer.sales.show', $sale));
        $invoiceResponse->assertStatus(200);
        $invoiceResponse->assertSee($sale->sale_number);
        $invoiceResponse->assertSee('Speaker Bluetooth');
    }
}
