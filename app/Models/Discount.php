<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'discount_type',
        'amount',
        'min_cart_total',
        'applies_to_all_categories',
        'active_from',
        'active_to'
    ];
 
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_discounts');
    }
}
