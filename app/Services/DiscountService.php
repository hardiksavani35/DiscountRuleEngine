<?php

namespace App\Services;

use App\Models\Discount;
use Carbon\Carbon;

class DiscountService
{
    public function applyDiscount($cart) : array {
        $todayDate = Carbon::now();
        $subTotal  = $cart['subtotal'];
        $items     = $cart['items'];  
        $categoryTotals = [];
 
        // Calculate the total amount for each category by summing the price * quantity of all items
        foreach ($items as $item) { 
            $categoryTotals[$item['category_id']] = ($categoryTotals[$item['category_id']] ?? 0) + ($item['price'] * $item['quantity']);
        }
        
        $discounts = Discount::where('active_from', '<=', $todayDate)
                        ->where('active_to', '>=', $todayDate) 
                        ->with('categories')
                        ->orderBy('id', 'desc')
                        ->get(); 
        
        foreach ($discounts as $discount) {
            $discountAmount = 0;

            if ($discount->applies_to_all_categories) {
                // If applies_to_all_categories is true, the discount is applied based on the subtotal only,
                // without checking category totals.
                if ($subTotal >= $discount->min_cart_total) {
                    $discountAmount = $this->calculateDiscount($subTotal, $discount);
                }
            } else {
                // If applies_to_all_categories is false, 
                // we check the total for each relevant category associated with the discount and ensure the total is >= min_cart_total.
                $categoryTotal = 0;
                foreach ($discount->categories as $category) {
                    $categoryTotal += $categoryTotals[$category->id] ?? 0;
                }
                
                if ($categoryTotal >= $discount->min_cart_total) {
                    $discountAmount = $this->calculateDiscount($subTotal, $discount);
                }
            } 
            if ($discountAmount > 0) {
                // Apply only the first discount that is eligible.
                return [
                    'cart_total'      => $subTotal,
                    'final_total'     => max(0, $subTotal - $discountAmount),         
                    'discount_value'  => $discountAmount,                   
                    'discount_detail' => [
                        'name' => $discount->name,
                        'type' => $discount->discount_type,
                        'amount' => $discount->amount,
                    ]
                ];
            }
        }
 
        return [
            'cart_total'       => $subTotal,
            'discount_value' => 0,
            'final_total'      => $subTotal
        ];
    }

    private function calculateDiscount($total, $discount) : float {
        if ($discount->discount_type === 'percentage') {
            return ($total * $discount->amount) / 100;
        }
        
        return min($total, $discount->amount);
    }
}