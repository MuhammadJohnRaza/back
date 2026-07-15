<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $staff = User::create([
            'name' => 'Inventory Staff Member',
            'email' => 'staff@example.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        // 2. Seed Categories
        $electronics = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'description' => 'Electronic devices, gadgets, and accessories.',
        ]);

        $furniture = Category::create([
            'name' => 'Furniture',
            'slug' => 'furniture',
            'description' => 'Office and home furniture items.',
        ]);

        $stationery = Category::create([
            'name' => 'Stationery',
            'slug' => 'stationery',
            'description' => 'Office supplies, papers, and writing materials.',
        ]);

        // 3. Seed Products
        // Electronics
        $iphone = Product::create([
            'category_id' => $electronics->id,
            'name' => 'iPhone 15 Pro',
            'slug' => 'iphone-15-pro',
            'sku' => 'ELEC-IPHONE15',
            'description' => 'Latest Apple flagship phone with titanium body.',
            'price' => 999.99,
            'quantity' => 15,
            'minimum_stock_level' => 5,
        ]);

        $macbook = Product::create([
            'category_id' => $electronics->id,
            'name' => 'MacBook Pro 16',
            'slug' => 'macbook-pro-16',
            'sku' => 'ELEC-MACPRO16',
            'description' => 'M3 Pro chip, 18GB Unified Memory, 512GB SSD.',
            'price' => 2499.99,
            'quantity' => 8,
            'minimum_stock_level' => 3,
        ]);

        $cable = Product::create([
            'category_id' => $electronics->id,
            'name' => 'USB-C Charging Cable',
            'slug' => 'usb-c-charging-cable',
            'sku' => 'ELEC-USBC-CABLE',
            'description' => '2-meter braided fast charging USB-C to USB-C cable.',
            'price' => 19.99,
            'quantity' => 2, // Low stock (min level 10)
            'minimum_stock_level' => 10,
        ]);

        // Furniture
        $chair = Product::create([
            'category_id' => $furniture->id,
            'name' => 'Ergonomic Office Chair',
            'slug' => 'ergonomic-office-chair',
            'sku' => 'FURN-ERG-CHAIR',
            'description' => 'High-back mesh chair with adjustable lumbar support.',
            'price' => 189.50,
            'quantity' => 12,
            'minimum_stock_level' => 5,
        ]);

        $desk = Product::create([
            'category_id' => $furniture->id,
            'name' => 'Adjustable Standing Desk',
            'slug' => 'adjustable-standing-desk',
            'sku' => 'FURN-STAND-DESK',
            'description' => 'Electric height adjustable desk with memory presets.',
            'price' => 399.99,
            'quantity' => 4, // Low stock (min level 5)
            'minimum_stock_level' => 5,
        ]);

        // Stationery
        $paper = Product::create([
            'category_id' => $stationery->id,
            'name' => 'A4 Copier Paper Pack',
            'slug' => 'a4-copier-paper-pack',
            'sku' => 'STAT-A4-PAPER',
            'description' => '80gsm high white copier paper (500 sheets pack).',
            'price' => 7.25,
            'quantity' => 45,
            'minimum_stock_level' => 15,
        ]);

        $pens = Product::create([
            'category_id' => $stationery->id,
            'name' => 'Gel Pen Black (Pack of 12)',
            'slug' => 'gel-pen-black-pack-of-12',
            'sku' => 'STAT-GEL-PEN-BLK',
            'description' => 'Smooth writing 0.5mm black gel pens.',
            'price' => 12.00,
            'quantity' => 8, // Low stock (min level 15)
            'minimum_stock_level' => 15,
        ]);

        // 4. Seed Stock Movements (initial auditing logs)
        StockMovement::create([
            'product_id' => $iphone->id,
            'user_id' => $admin->id,
            'type' => 'in',
            'quantity' => 15,
            'reason' => 'Initial inventory count load.',
        ]);

        StockMovement::create([
            'product_id' => $macbook->id,
            'user_id' => $admin->id,
            'type' => 'in',
            'quantity' => 10,
            'reason' => 'Stock shipment arrived.',
        ]);

        StockMovement::create([
            'product_id' => $macbook->id,
            'user_id' => $staff->id,
            'type' => 'out',
            'quantity' => 2,
            'reason' => 'Assigned to new department staff.',
        ]);

        StockMovement::create([
            'product_id' => $cable->id,
            'user_id' => $staff->id,
            'type' => 'in',
            'quantity' => 5,
            'reason' => 'Restock from local vendor.',
        ]);

        StockMovement::create([
            'product_id' => $cable->id,
            'user_id' => $staff->id,
            'type' => 'out',
            'quantity' => 3,
            'reason' => 'Defective items returned.',
        ]);
    }
}
