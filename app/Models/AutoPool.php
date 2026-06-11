<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoPool extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'count_4',
        'count_16',
        'count_64',
        'count_256',
        'count_1024',
        'count_4096',
        'count_16384',
    ];
    public function joiningKit(){
        return $this->hasOne(JoiningKit::class,'autopool_id','id');
    }
}
