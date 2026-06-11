<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsEvent extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'image',
        'status',
    ];
    protected $appends = ['image_path'];
    public function getImagePathAttribute(){
        return asset('storage/uploads/news_events/'. $this->image);
    }
}
