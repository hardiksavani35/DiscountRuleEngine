<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Services\DiscountService;
use Illuminate\Http\Request; 

class DiscountController extends Controller
{
    protected $discountService;

    public function __construct(DiscountService $discountService) {
        $this->discountService = $discountService;
    }

    public function applyDiscount(Request $request) {
        $validator = Validator::make($request->all(), [
            'subtotal'              => 'required|numeric|min:0',
            'items'                 => 'required|array',  
            'items.*.product_title' => 'required|string|max:255',
            'items.*.category_id'   => 'required|integer|exists:categories,id',
            'items.*.quantity'      => 'required|integer|min:1', 
            'items.*.price'         => 'required|numeric|min:0.01', 
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => 'Validation failed', 'details' => $validator->errors()], 400);
        }
 
        try {
            $data    = $this->discountService->applyDiscount($request->all());
            $message = count($data) > 3 ? "Discount successfully applied." : "No discounts apply to the current cart total.";
            $status  = true;
            $code    = 200;
        } catch (Exception $e) { 
            $data    = [];
            $message = "An unexpected error occurred: " . $e->getMessage();
            $status  = false;
            $code    = 500;
        }

        return response()->json([
            'status'  => $status,
            'message' => $message,
            'data'    => $data
        ], $code);
    }
}
