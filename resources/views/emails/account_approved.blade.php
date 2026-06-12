<x-mail::message>
# Account Approved!

Hello {{ $user->name }},

We are pleased to inform you that your registration request has been approved and your account is now active.

<x-mail::panel>
**Account Details:**
- **User ID:** {{ $user->member_id }}
- **Name:** {{ $user->name }}
- **Deposit Amount:** ${{ number_format($depositAmount, 2) }}
- **Package:** {{ $packageName }}
</x-mail::panel>

You can now log in to your dashboard to access all features.

<x-mail::button :url="route('login')">
Login Now
</x-mail::button>

Best Regards,<br>
The {{ config('app.name') }} Team
</x-mail::message>
