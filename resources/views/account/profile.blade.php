@extends('layout.main')
@section('content')
@php
    $authMember = auth('member')->user();
@endphp
    <x-page-breadcrumb current-page='My Profile' sub-menu='My Information' />
    <div class="row">
        <div class="col-md-12">
            <div class="card overflow-visible">
                <div class="card-body p-0">
                    <div class="profile-header bg-main" style="height: 150px; position: relative;">
                    </div>
                    <div class="row px-4" style="margin-top: -60px;">
                        <div class="col-md-3 text-center">
                            <div class="profile-avatar-container shadow-lg" style="background: white; padding: 5px; border-radius: 50%; display: inline-block; position: relative;">
                                <img src="{{ $authMember->profile_image_url }}" 
                                    title="Click to change"
                                    style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; cursor: pointer;"
                                    class="profile-image-det shadow-sm" onclick="document.getElementById('profile_image').click();" />
                                <div class="avatar-edit-icon bg-white shadow-sm" style="position: absolute; bottom: 10px; right: 10px; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;" onclick="document.getElementById('profile_image').click();">
                                    <i class="icon-camera text-primary"></i>
                                </div>
                            </div>
                            <h4 class="mt-3 font-weight-bold">{{ $authMember->name }}</h4>
                            <p class="text-muted">{{ $authMember->member_id }}</p>
                            
                            <form method="post" id="profile_image_form" class="mt-2">
                                @csrf
                                {!! Form::hidden('user_id', $authMember->id, ['id'=>'user_id']) !!}
                                <input type="file" name="profile_image" id="profile_image" class="form-control" style="display:none;" />
                                {!! Form::submit('Update Photo', ['class'=>'btn btn-main btn-sm mb-4', 'id' => 'update_photo_btn', 'style' => 'display: none;']) !!}
                            </form>
                        </div>
                        <div class="col-md-9 pt-md-5 mt-md-4">
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="m-0 font-weight-bold">Personal Information</h5>
                                    <a href="{{ route('account.profile-edit') }}" class="btn btn-main btn-sm">
                                        <i class="icon-pencil mr-1"></i> Edit Profile
                                    </a>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="info-group">
                                        <label class="text-muted small text-uppercase font-weight-bold mb-1">Full Name</label>
                                        <p class="h6 font-weight-bold">{{ $authMember->name }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="info-group">
                                        <label class="text-muted small text-uppercase font-weight-bold mb-1">Email Address</label>
                                        <p class="h6 font-weight-bold">{{ $authMember->email }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="info-group">
                                        <label class="text-muted small text-uppercase font-weight-bold mb-1">Member ID</label>
                                        <p class="h6 font-weight-bold text-primary">{{ $authMember->member_id }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="info-group">
                                        <label class="text-muted small text-uppercase font-weight-bold mb-1">Mobile Number</label>
                                        <p class="h6 font-weight-bold">{{ $authMember->mobile }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="info-group">
                                        <label class="text-muted small text-uppercase font-weight-bold mb-1">Joined Date</label>
                                        <p class="h6 font-weight-bold">{{ $authMember->created_at->toDayDateTimeString() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>@endsection
@section('css')
    @parent
    <style type="text/css">

    </style>
@endsection
@section('scripts')
@parent
<script>
$(document).ready(function() {
    // Handle file input change for preview
    $('#profile_image').on('change', function() {
        var file_data = $(this).prop('files')[0];
        if (file_data) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('.profile-image-det').attr('src', e.target.result);
                $('#update_photo_btn').fadeIn();
            }
            reader.readAsDataURL(file_data);
        }
    });

    // Handle form submission via AJAX
    $('#profile_image_form').on('submit', function(e) {
        e.preventDefault();

        var file_data = $('#profile_image').prop('files')[0];
        // if (!file_data) {
        //     alert('Please select a file to upload.');
        //     return;
        // }

        var form_data = new FormData();
        form_data.append('profile_image', file_data);
        form_data.append('user_id', $('#user_id').val());
        form_data.append('_token', $('input[name="_token"]').val());

        $.ajax({
            url: '{{ route("member.profile.image.update") }}',
            type: 'POST',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status) {
                    $('#profile_image_form').append(`<div class="alert alert-success mt-2">${response.message}</div>`);
                    setTimeout(() => {
                        location.reload();
                    }, 800);
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    });
});
</script>
@endsection

