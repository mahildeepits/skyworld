<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPayout extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'income_type',
        'amount',
        'tds',
        'admin_charges',
        'net_amount',
        'is_requested',
        'is_paid',
        'transaction_id'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
