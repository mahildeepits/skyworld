@extends('layout.main')
@section('content')
@php
$user = authUser();
@endphp
    <x-page-breadcrumb :currentPage="'Contact Us'" />
    <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        
                        <div class="d-sm-block d-md-flex justify-content-between align-items-baseline mb-3">
                            <h5 class="card-title">Get In Touch With Us</h5>
                            <p class=>
                                Our Email : <span>{{env('MAIL_FROM_ADDRESS')}}</span> 
                            </p>
                        </div>
                        <form action="{{ route('contact.submit') }}" method="POST" onsubmit="ajaxFormSubmit($(this))">
                            @csrf
                            <div class="form-group">
                                <label for="name">Your Name</label>
                                <input type="text" class="form-control" id="name" value="{{$user->name ?? ''}}" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Your Email</label>
                                <input type="email" class="form-control" id="email" value="{{$user->email ?? ''}}" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Your Phone</label>
                                <input type="phone" class="form-control" value="{{$user->mobile ?? ''}}" id="phone" name="phone" required>
                            </div>
                            <div class="form-group">
                                <label for="message">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn bg-main text-white mt-3">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-center">
                {{-- <h4>Contact details</h4>
                <p>Email : </p>
                <p>Phone : <span>{{env('MOBILE_NUMBER') ?? '9876543210'}}</span></p> --}}
                <img src="{{asset('images/contactus.jpg')}}" alt="conact" width="80%">
            </div>
    </div>@endsection