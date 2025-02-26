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

    // There are two discount records: one with a minimum cart total of $600 and another with a minimum cart total of $300.

    // The $300 discount requires the total for Electronics (category_id = 1) and Furniture (category_id = 3) to be $300 or more, 
    // but here it is only $200, so this discount does not apply.

    // The $600 discount applies because the subtotal is $700, and there are no category restrictions.
    public function test_applyDiscount_with_percentage_discount()
    {
        $cart = [
            'subtotal' => 700,
            'items' => [
                ["id" => 1, "product_title" => "Laptop", "category_id" => 1, "quantity" => 1, "price" => 100],
                ["id" => 1, "product_title" => "T-shirt", "category_id" => 2, "quantity" => 5, "price" => 100],
                ["id" => 1, "product_title" => "Table", "category_id" => 3, "quantity" => 1, "price" => 100]
            ]
        ];
 
        $discountService = new DiscountService(); 
        $result = $discountService->applyDiscount($cart); 

        // Assert
        $this->assertEquals(700, $result['cart_total']);
        $this->assertEquals(630, $result['final_total']);
        $this->assertEquals(70, $result['discount_value']);
    }
}
