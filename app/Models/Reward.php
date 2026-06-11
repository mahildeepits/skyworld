<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = ['pairs','name','rank','image','days','amount','description','type'];
    protected $appends = ['image_path'];
    protected $casts = [
        'description' => 'array',
    ];

    public function getImagePathAttribute(){
        return asset('storage/reward_images/'. $this->image);
    }
}
