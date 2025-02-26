<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Services\DiscountService;
use Tests\TestCase;

class CategorySpecificDiscountAvailable extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_applyDiscount_with_category_based_discount()
    {
        $cart = [
            'subtotal' => 1300,
            'items' => [
                ["id" => 1, "product_title" => "Smartphone", "category_id" => 1, "quantity" => 2, "price" => 100],
                ["id" => 1, "product_title" => "T-Shirt", "category_id" => 2, "quantity" => 10, "price" => 1000],
                ["id" => 1, "product_title" => "Table", "category_id" => 3, "quantity" => 1, "price" => 100]                          
            ]
        ];

        $discountService = new DiscountService(); 
        $result = $discountService->applyDiscount($cart); 

        // Assert
        $this->assertEquals(1300, $result['cart_total']);
        $this->assertEquals(1200, $result['final_total']);
        $this->assertEquals(100, $result['discount_value']);
    }
}
