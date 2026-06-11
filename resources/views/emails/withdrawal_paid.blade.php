<x-mail::message>
# Withdrawal Processed Successfully!

Hello {{ $user->name }},

We are pleased to inform you that your withdrawal request has been processed and paid successfully.

<x-mail::panel>
**Transaction Details:**
- **Amount:** ${{ number_format($transaction->amount, 2) }}
- **TDS Deducted:** ${{ number_format($transaction->transaction_fees, 2) }}
- **Net Paid Amount:** ${{ number_format($transaction->net_amount, 2) }}
- **Wallet Address:** {{ $transaction->wallet_address }}
- **Transaction Hash:** {{ $transaction->transaction_hash }}
</x-mail::panel>

You can track your transaction on the blockchain using the hash provided above or view it in your dashboard.

<x-mail::button :url="route('member.dashboard')">
View Dashboard
</x-mail::button>

If you have any questions, please feel free to contact our support team.

Best Regards,<br>
The {{ config('app.name') }} Team
</x-mail::message>
