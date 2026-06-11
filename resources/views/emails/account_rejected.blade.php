<x-mail::message>
# Account Registration Rejected

Hello {{ $user->name }},

We regret to inform you that your registration request has been rejected after reviewing your details. Consequently, your account has been deactivated.

If you have any questions or believe this is an error, please contact our support team.

Best Regards,<br>
The {{ config('app.name') }} Team
</x-mail::message>
