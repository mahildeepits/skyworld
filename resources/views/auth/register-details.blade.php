@extends('layout.auth')

@section('content')
<div class="auth-form-light text-left p-5">
    <div class="brand-logo text-center">
        <img src="{{ asset('images/54.png') }}" style="width:180px;" alt="logo">
    </div>
    <div class="text-center mb-4">
        <h3 class="mb-2">Registration Details</h3>
        <p class="font-weight-light">Welcome to {{ config('app.name') }}</p>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <tbody>
                <tr>
                    <th width="120" class="pl-0">User ID</th>
                    <td class="pr-0">{{ @$tempArray['user_id'] }}</td>
                </tr>
                <tr>
                    <th class="pl-0">Password</th>
                    <td class="pr-0">{{ @$tempArray['password'] }}</td>
                </tr>
                <tr>
                    <th class="pl-0">Email ID</th>
                    <td class="pr-0">{{ @$tempArray['email'] }}</td>
                </tr>
                <tr>
                    <th class="pl-0">Sponsor</th>
                    <td class="pr-0">{{ @$tempArray['sponsor'] }} ({{ @$tempArray['sponsor_name'] }})</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="mt-4 text-center">
        <a href="{{ route('login') }}" class="btn btn-main btn-lg font-weight-medium auth-form-btn text-white w-100">Login Now</a>
    </div>
</div>
@endsection
