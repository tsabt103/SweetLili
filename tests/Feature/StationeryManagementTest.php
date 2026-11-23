<?php

namespace Tests\Feature;

use App\Jobs\CheckLowStockJob;
use App\Livewire\ProductManagement;
use App\Livewire\ProductTrash;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class SweetLiliManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test Manual Authentication: Login.
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@SweetLili.test',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@SweetLili.test',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test Manual Authentication: Register.
     */
    public function test_user_can_register_manually(): void
    {
        $response = $this->post('/register', [
            'name' => 'Tsabita ist',
            'email' => 'tsabita@artsupply.test',
            'password' => 'artsecret123',
            'password_confirmation' => 'artsecret123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        $user = User::where('email', 'tsabita@artsupply.test')->first();
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('artsecret123', $user->password));
    }

    /**
     * Test Manual Authentication: Logout.
     */
    public function test_authenticated_user_can_logout(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     * Test Dashboard and Cache::remember.
     */
    public function test_dashboard_displays_stats_and_uses_cache(): void
    {
        $user = User::first();

        // Bersihkan cache awal
        Cache::forget('dashboard_stats');
        $this->assertFalse(Cache::has('dashboard_stats'));

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Total Katalog Produk');
        $response->assertSee('Total Stok Fisik');
        $response->assertSee('Stok Menipis');

        // Pastikan cache tersimpan
        $this->assertTrue(Cache::has('dashboard_stats'));

        // Ubah produk, cache harus otomatis di-flush
        $product = Product::first();
        $product->update(['stock' => 50]);

        $this->assertFalse(Cache::has('dashboard_stats'));
    }

    /**
     * Test Livewire Product Management (CRUD & Soft Delete).
     */
    public function test_livewire_product_crud_and_soft_delete(): void
    {
        $user = User::first();
        $this->actingAs($user);

        // Test render component
        Livewire::test(ProductManagement::class)
            ->assertStatus(200)
            ->assertSee('Notebook A5')
            ->assertSee('Watercolor Set');

        // Test Tambah Produk baru via Livewire
        Livewire::test(ProductManagement::class)
            ->set('name', 'Calligraphy Dip Pen')
            ->set('category', 'Pena & Spidol Seni')
            ->set('price', '75000')
            ->set('stock', '5') // Stok menipis
            ->set('status', 'active')
            ->set('description', 'Pena celup kaligrafi dengan 5 nibs')
            ->call('saveProduct')
            ->assertHasNoErrors();

        $newProduct = Product::where('name', 'Calligraphy Dip Pen')->first();
        $this->assertNotNull($newProduct);
        $this->assertEquals(5, $newProduct->stock);

        // Test Soft Delete via Livewire
        Livewire::test(ProductManagement::class)
            ->call('confirmDelete', $newProduct->id)
            ->call('deleteProduct');

        $this->assertSoftDeleted('products', ['id' => $newProduct->id]);

        // Test Restore di Trash
        Livewire::test(ProductTrash::class)
            ->assertSee('Calligraphy Dip Pen')
            ->call('restore', $newProduct->id);

        $this->assertNotSoftDeleted('products', ['id' => $newProduct->id]);
    }

    /**
     * Test REST API Endpoints with Sanctum Bearer Token.
     */
    public function test_rest_api_lifecycle(): void
    {
        // 1. Login via API untuk dapat token
        $loginRes = $this->postJson('/api/auth/login', [
            'email' => 'admin@SweetLili.test',
            'password' => 'password123',
        ]);

        $loginRes->assertStatus(200);
        $loginRes->assertJsonStructure(['success', 'token', 'user']);
        $token = $loginRes->json('token');

        // 2. GET /api/products (Katalog)
        $listRes = $this->getJson('/api/products');
        $listRes->assertStatus(200);
        $listRes->assertJsonStructure(['success', 'data', 'pagination']);

        // 3. POST /api/products (Tambah produk terproteksi token)
        $storeRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/products', [
                'name' => 'Linocut Carving Tool Set',
                'category' => 'Kuas & Peralatan',
                'price' => 125000,
                'stock' => 7,
                'status' => 'active',
                'description' => 'Pisau cukil lino isi 5 mata pisau',
            ]);

        $storeRes->assertStatus(201);
        $storeRes->assertJsonStructure(['success', 'data' => ['id', 'name', 'price']]);
        $createdId = $storeRes->json('data.id');

        // 4. GET /api/products/{id}
        $showRes = $this->getJson("/api/products/{$createdId}");
        $showRes->assertStatus(200);
        $showRes->assertJsonPath('data.name', 'Linocut Carving Tool Set');

        // 5. PUT /api/products/{id}
        $updateRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson("/api/products/{$createdId}", [
                'price' => 135000,
                'stock' => 20,
            ]);

        $updateRes->assertStatus(200);
        $updateRes->assertJsonPath('data.price', 135000);

        // 6. DELETE /api/products/{id} (Soft Delete)
        $deleteRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson("/api/products/{$createdId}");

        $deleteRes->assertStatus(200);
        $this->assertSoftDeleted('products', ['id' => $createdId]);
    }

    /**
     * Test CheckLowStockJob and stock alert creation.
     */
    public function test_low_stock_job_creates_alert(): void
    {
        $product = Product::where('stock', '<=', 10)->first();
        $this->assertNotNull($product);

        $job = new CheckLowStockJob($product);
        $job->handle();

        $this->assertDatabaseHas('stock_alerts', [
            'product_id' => $product->id,
            'alert_type' => 'low_stock',
        ]);
    }
}
