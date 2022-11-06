<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'category_id', 
        'price', 
        'discount', 
        'image'
    ];

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }


}
