<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Services\DiscountService;
use Tests\TestCase;

class PercentageDiscountAvailable extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_applyDiscount_with_percentage_discount()
    {
        $cart = [
            'subtotal' => 600,
            'items' => [
                ["id" => 1, "product_title" => "Laptop", "category_id" => 1, "quantity" => 1, "price" => 500],
                ["id" => 1, "product_title" => "Smartphone", "category_id" => 1, "quantity" => 1, "price" => 100]
            ]
        ];
 
        $discountService = new DiscountService(); 
        $result = $discountService->applyDiscount($cart); 

        // Assert
        $this->assertEquals(600, $result['cart_total']);
        $this->assertEquals(540, $result['final_total']);
        $this->assertEquals(60, $result['discount_value']);
    }
}
