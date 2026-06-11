<?php

namespace App\Models;

use App\Models\AdminCharge;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'keyword',
        'transfered_to',
        'amount',
        'tds',
        'admin_charges',
        'net_amount',
        'pin_no',
        'status',
        'transaction_id',
    ];

    protected $appends = [
        'slug',
    ];

    // New protected array for keywords and names
    protected $keywordNames = [
        'self_transfer_direct'      => 'Self Transfered Direct Income',
        'self_transfer_level'       => 'Self Transfered Team Performance',
        'self_transfer_autopool'    => 'Self Transfered AutoPool Income',
        'buy_pin'                   => 'Pin Purchased ',
        'transfer'                  => 'Transfered Money To',
        'self_topup'                => 'Self Topup',
        'user_topup'                => 'User topup',
        'pin_transfer'              => 'Received Money From ',
    ];

    public function getKeywordNames()
    {
        return $this->keywordNames;
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','member_id');
    }
    public function transferedTo(){
        return $this->belongsTo(User::class,'transfered_to','member_id');
    }
    public function epin() {
        return $this->belongsTo(Epin::class, 'pin_no', 'pin_no');
    }

    public function setAmountAttribute($value)
    {
        $adminCharge = AdminCharge::first(); // Fetch AdminCharge settings

        // $tds = ($value * $adminCharge->tds_charges) / 100;
        // $admin_charges = ($value * $adminCharge->admin_charges) / 100;
        $net_amount = (
            $value 
            // - $tds 
            // - $admin_charges
    );

        $this->attributes['amount'] = $value;
        // $this->attributes['tds'] = $tds;
        // $this->attributes['admin_charges'] = $admin_charges;
        $this->attributes['net_amount'] = $net_amount;
    }

    public function getSlugAttribute() {
        $transactionText = 'N/A';
        // Generate transaction text based on the keyword
        if (isset($this->keywordNames[$this->keyword])) {
            $transactionText = $this->keywordNames[$this->keyword];
            if ($this->keyword === 'buy_pin') {
                $transactionText = "$transactionText: {$this->pin_no}";
            } elseif ($this->keyword === 'transfer') {
                if (authUser()->member_id == $this->transfered_to) {
                    $transactionText = "Received Money From  {$this?->user?->name}";
                } else {
                    $transactionText = "$transactionText {$this->transferedTo->member_id} ({$this->transferedTo->name})";
                }
            } elseif ($this->keyword === 'self_topup') {
                // Assuming you have a relation to get the joining kit
                $joiningKitName = $this?->epin?->joining_kit_rel?->name ?? 'Unknown Kit'; // Adjust based on your relationships
                $transactionText = "Self Topup with $joiningKitName Kit";
            } elseif ($this->keyword === 'user_topup') {
                // Assuming you have a relation to get the used_by user and joining kit
                $usedByUser = User::find($this?->epin?->used_by); // Adjust based on your actual field
                $joiningKitName = $this?->epin?->joining_kit_rel?->name ?? 'Unknown Kit'; // Adjust based on your relationships
                $transactionText = "User ({$usedByUser->member_id}) Topup with $joiningKitName Kit"; // Adjust based on your actual field
            } else if ($this->keyword == 'pin_transfer') {
                $transactionText = "$transactionText: {$this->user->name}";
            }
        }
        return $transactionText;
    }
}
