<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositsTracking extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'type',
        'transaction_hash',
        'paid_from_address',
        'confirmed_at',
    ];
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
