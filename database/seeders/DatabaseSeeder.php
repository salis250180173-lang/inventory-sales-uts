<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Akun Demo (Owner, Admin, User)
        User::create([
            'name' => 'Owner Toko',
            'email' => 'owner@inventory.test',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        User::create([
            'name' => 'Admin Sistem',
            'email' => 'admin@inventory.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'User Biasa',
            'email' => 'user@inventory.test',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // 2. Buat Data Kategori Awal
        $catElektronik = Category::create(['name' => 'Elektronik']);
        $catATK = Category::create(['name' => 'Alat Tulis Kantor']);

        // 3. Buat Data Supplier Awal
        Supplier::create([
            'name' => 'PT Sumber Jaya Abadi',
            'phone' => '081234567890',
            'email' => 'info@sumberjaya.com',
        ]);

        // 4. Buat Data Produk Awal
        Product::create([
            'category_id' => $catElektronik->id,
            'sku' => 'ELK-001',
            'name' => 'Printer Epson L3210',
            'purchase_price' => 2000000,
            'selling_price' => 2500000,
            'stock' => 10,
            'minimum_stock' => 3,
        ]);

        Product::create([
            'category_id' => $catATK->id,
            'sku' => 'ATK-001',
            'name' => 'Kertas HVS A4 70gr',
            'purchase_price' => 45000,
            'selling_price' => 55000,
            'stock' => 25,
            'minimum_stock' => 5,
        ]);
    }
}
