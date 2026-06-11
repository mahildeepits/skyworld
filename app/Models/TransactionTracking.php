<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'keyword',
        'from_user',
        'amount',
        'transaction_fee',
        'net_amount',
        'remark',
        'count',
        'related_id',
    ];

    protected $appends = [
        'type_name',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function package() {
        return $this->belongsTo(AgentCategory::class, 'package_id', 'id');
    }
    public function fromUser() {
        return $this->belongsTo(User::class, 'from_user', 'id');
    }

    public function getTypeNameAttribute() {
        $typeNames = [
            'deposit'       => 'Deposit',
            'withdrawl'     => 'Withdrawal',
            'withdrawal'    => 'Withdrawal',
            'direct'        => 'Direct Income',
            'sponsor'       => 'Sponsor Income',
            'reward'        => 'Reward Income',
            'ambasdor'      => 'Ambassador Income',
            'task'          => 'Task Income',
            'community'     => 'Community Income',
            'upline'        => 'Upline Income',
            'level'         => 'Level Income',
        ];
        return $typeNames[$this->type] ?? ucwords(str_replace('_', ' ', $this->type));
    }
}
