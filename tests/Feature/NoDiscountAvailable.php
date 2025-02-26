<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Services\DiscountService;
use Tests\TestCase;

class NoDiscountAvailable extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_applyDiscount_when_no_discount_is_available()
    {
        $cart = [
            'subtotal' => 100,
            'items' => [
                ["id" => 1, "product_title" => "Laptop", "category_id" => 1, "quantity" => 1, "price" => 500]
            ]
        ];
 
        $discountService = new DiscountService(); 
        $result = $discountService->applyDiscount($cart); 

        // Assert
        $this->assertEquals(100, $result['cart_total']);
        $this->assertEquals(100, $result['final_total']);
        $this->assertEquals(0, $result['discount_value']);
    }
}
