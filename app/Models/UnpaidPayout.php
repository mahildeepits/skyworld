<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnpaidPayout extends Model
{
    use HasFactory;

    protected $fillable = ['username','tree','pair_count','pair_amount','direct_income','tds','admin_charges','net_amount','credit_or_cut'];
}
