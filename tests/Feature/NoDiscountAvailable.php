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

    //This discount will not be applicable because there are two available discounts:

    //A discount requiring a minimum cart total of $600 with applies_to_all_categories = true (no category restrictions).
    //A discount requiring a minimum cart total of $200 with applies_to_all_categories = false (specific category restriction).
    //Since the subtotal is $500, the second discount meets the minimum cart total requirement. However, 
    //it also requires the total for Electronics and Furniture to be $300 or more, which is not met. Therefore, no discount will be applied.
    public function test_applyDiscount_when_no_discount_is_available()
    {
        $cart = [
            'subtotal' => 500,
            'items' => [
                ["id" => 1, "product_title" => "T-shirt", "category_id" => 2, "quantity" => 5, "price" => 100]
            ]
        ];
 
        $discountService = new DiscountService(); 
        $result = $discountService->applyDiscount($cart);  
        // Assert
        $this->assertEquals(500, $result['cart_total']);
        $this->assertEquals(500, $result['final_total']);
        $this->assertEquals(0, $result['discount_value']);
    }
}
