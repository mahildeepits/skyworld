<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'image'
    ];
    protected $appends = ['image_path'];
    public function getImagePathAttribute(){
        return $this->image ? asset('storage/uploads/announcements/'. $this->image) : null;
    }
}
