<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyTrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trade_date',
        'rate',
        'base_amount',
        'profit_amount',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
