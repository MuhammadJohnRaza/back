<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// -----------------------------------------------------------------------
// Authentication Tests
// -----------------------------------------------------------------------

test('user can login and retrieve auth token', function () {
    $user = User::factory()->create([
        'email'    => 'user@example.com',
        'password' => bcrypt('password'),
        'role'     => 'staff',
    ]);

    $response = $this->postJson('/api/v1/login', [
        'email'    => 'user@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'access_token',
            'token_type',
            'user' => ['id', 'name', 'email', 'role'],
        ]);
});

test('invalid credentials return 401', function () {
    User::factory()->create(['email' => 'user@example.com', 'password' => bcrypt('password')]);

    $this->postJson('/api/v1/login', [
        'email'    => 'user@example.com',
        'password' => 'wrong-password',
    ])->assertStatus(401);
});

test('unauthenticated users cannot access protected routes', function () {
    $this->getJson('/api/v1/products')->assertStatus(401);
});

// -----------------------------------------------------------------------
// Authorization Tests (Admin vs Staff)
// -----------------------------------------------------------------------

test('staff users cannot create products', function () {
    $staff    = User::factory()->create(['role' => 'staff']);
    $category = Category::factory()->create();

    $this->actingAs($staff, 'sanctum')
        ->postJson('/api/v1/products', [
            'category_id' => $category->id,
            'name'        => 'Test Product',
            'sku'         => 'TEST-001',
            'price'       => 9.99,
        ])
        ->assertStatus(403);
});

test('staff users cannot create categories', function () {
    $staff = User::factory()->create(['role' => 'staff']);

    $this->actingAs($staff, 'sanctum')
        ->postJson('/api/v1/categories', [
            'name' => 'New Category',
        ])
        ->assertStatus(403);
});

test('admin users can create products', function () {
    $admin    = User::factory()->create(['role' => 'admin']);
    $category = Category::factory()->create();

    $response = $this->actingAs($admin, 'sanctum')
        ->postJson('/api/v1/products', [
            'category_id'         => $category->id,
            'name'                => 'iPhone 15',
            'sku'                 => 'ELEC-IPHONE15',
            'price'               => 999.99,
            'quantity'            => 10,
            'minimum_stock_level' => 5,
        ]);

    $response->assertStatus(201)
        ->assertJsonPath('data.name', 'iPhone 15')
        ->assertJsonPath('data.sku', 'ELEC-IPHONE15');

    $this->assertDatabaseHas('products', [
        'sku'      => 'ELEC-IPHONE15',
        'quantity' => 10,
    ]);
});

test('admin users can delete products', function () {
    $admin   = User::factory()->create(['role' => 'admin']);
    $product = Product::factory()->create();

    $this->actingAs($admin, 'sanctum')
        ->deleteJson("/api/v1/products/{$product->id}")
        ->assertStatus(200);

    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});

test('staff users cannot delete products', function () {
    $staff   = User::factory()->create(['role' => 'staff']);
    $product = Product::factory()->create();

    $this->actingAs($staff, 'sanctum')
        ->deleteJson("/api/v1/products/{$product->id}")
        ->assertStatus(403);
});

// -----------------------------------------------------------------------
// Stock Movement Tests
// -----------------------------------------------------------------------

test('recording stock IN movement increases product quantity', function () {
    $staff   = User::factory()->create(['role' => 'staff']);
    $product = Product::factory()->create(['quantity' => 10]);

    $this->actingAs($staff, 'sanctum')
        ->postJson('/api/v1/stock-movements', [
            'product_id' => $product->id,
            'type'       => 'in',
            'quantity'   => 5,
            'reason'     => 'Supplier delivery',
        ])
        ->assertStatus(201);

    expect($product->fresh()->quantity)->toBe(15);
});

test('recording stock OUT movement decreases product quantity', function () {
    $staff   = User::factory()->create(['role' => 'staff']);
    $product = Product::factory()->create(['quantity' => 10]);

    $this->actingAs($staff, 'sanctum')
        ->postJson('/api/v1/stock-movements', [
            'product_id' => $product->id,
            'type'       => 'out',
            'quantity'   => 3,
            'reason'     => 'Customer order',
        ])
        ->assertStatus(201);

    expect($product->fresh()->quantity)->toBe(7);
});

test('prevent stock overdraw returns 422 with validation error', function () {
    $staff   = User::factory()->create(['role' => 'staff']);
    $product = Product::factory()->create(['quantity' => 10]);

    $this->actingAs($staff, 'sanctum')
        ->postJson('/api/v1/stock-movements', [
            'product_id' => $product->id,
            'type'       => 'out',
            'quantity'   => 20, // more than available
            'reason'     => 'Customer order',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['quantity']);
});

// -----------------------------------------------------------------------
// Reports Tests
// -----------------------------------------------------------------------

test('low stock report returns products below minimum level', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    Product::factory()->lowStock()->create();
    Product::factory()->wellStocked()->create();

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/reports/low-stock');

    $response->assertStatus(200)
        ->assertJsonStructure(['data' => [['id', 'name', 'sku', 'quantity']]]);

    // Only the low-stock product should appear
    expect(count($response->json('data')))->toBe(1);
});

test('summary report returns key inventory metrics', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    Product::factory()->count(3)->create();

    $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/reports/summary')
        ->assertStatus(200)
        ->assertJsonStructure(['data' => [
            'total_products',
            'total_categories',
            'total_stock_value',
            'low_stock_count',
            'movements_today',
        ]]);
});
