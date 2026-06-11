<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = ['username','tree','pair_count','pair_amount','direct_income','tds','admin_charge','net_amount', 'level', 'level_income'];

    public function user_rel(){
        return $this->belongsTo(User::class,'member_id','username');
    }
}
