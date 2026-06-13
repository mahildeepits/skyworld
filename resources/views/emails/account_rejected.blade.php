<x-mail::message>
# Account Registration Rejected

Hello {{ $user->name }},

We regret to inform you that your registration request has been rejected after reviewing your details. However, you can still log in to your dashboard and resubmit your registration request with the correct details.

If you have any questions or believe this is an error, please contact our support team.

Best Regards,<br>
The {{ config('app.name') }} Team
</x-mail::message>
