<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAgentCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'agent_category_id',
        'is_active',
    ];  
    public function agentCategory()
    {
        return $this->belongsTo(AgentCategory::class);
    }
}
