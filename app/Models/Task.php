<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'title_description',
        'product_type',
        'status',
        'order_date',
        'order_number',
        'reviews'
    ];
    protected $casts = [
        'reviews' => 'array',
    ];
}
