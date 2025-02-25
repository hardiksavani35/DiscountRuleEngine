<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Discount;
use App\Models\Category;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Global discount (applies to all categories)
        Discount::create([
            'name' => '10% Discount on all categories',
            'discount_type' => 'percentage',
            'amount' => 10,
            'min_cart_total' => 1500,
            'applies_to_all_categories' => true,
            'active_from' => now(),
            'active_to' => now()->addMonths(1)
        ]);

        // Linking discount to specific categories (Electronics, Furniture)  
        $discount = Discount::create([
            'name' => 'Fixed $100 Off – Electronics & Furniture',
            'discount_type' => 'fixed',
            'amount' => 100,
            'min_cart_total' => 1000,
            'applies_to_all_categories' => false,
            'active_from' => now(),
            'active_to' => now()->addMonths(1)
        ]);
        
        $electronics = Category::where('name', 'Electronics')->first();
        $furniture = Category::where('name', 'Furniture')->first();

        $discount->categories()->attach($electronics->id, ['created_at' => now(), 'updated_at' => now()]);
        $discount->categories()->attach($furniture->id, ['created_at' => now(), 'updated_at' => now()]);
    }
}
