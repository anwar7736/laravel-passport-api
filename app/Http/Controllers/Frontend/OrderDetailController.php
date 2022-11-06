<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    protected $fillable = [
        'order_id',
        'category_id',
        'product_id',
        'quantity',
        'price',
        'discount',
        'total',
    ];
}
