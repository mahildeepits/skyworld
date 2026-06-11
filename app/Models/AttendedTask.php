<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendedTask extends Model
{
    use HasFactory;
    protected $fillable = [
        'task_id',
        'user_id',
        'category_id',
        'rating',
        'review',
        'completed_at',
    ];
    public function task(){
        return $this->belongsTo(Task::class,'task_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
