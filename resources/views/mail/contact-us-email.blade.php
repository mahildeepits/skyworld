@component('mail::message')
# Hello {{ $data['name'] }},

We have recieved your message and will get back to you as soon as possible.

Thank you for choosing {{ config('app.name') }}.

Please continue your business with us.

Thanks,  
{{ config('app.name') }}

<p align="center">
    <img src="{{ asset('images/54.png') }}" alt="{{ config('app.name') }}">
</p>
@endcomponent