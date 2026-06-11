<x-mail::message>
# Registration OTP

Hello,

Thank you for registering. Please use the following OTP to complete your registration process:

# {{ $otp }}

This OTP will expire in 10 minutes.

If you did not initiate this registration, please ignore this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
