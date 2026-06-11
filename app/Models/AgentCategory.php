<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'image',
        'unlock_balance',
        'massive_order_rate',
        'daily_order_limit',
        'community_bonus_rate',
        'valid_downline',
        'team_a',
        'team_b_c',
        'team_a_profit',
        'team_b_profit',
        'team_c_profit',
        'level_upgrade_income',
        'required_points',
    ];
    protected $appends = ['image_path'];
    public function getImagePathAttribute(){
        return asset('storage/uploads/agent-categories/'. $this->image);    
    }
}
