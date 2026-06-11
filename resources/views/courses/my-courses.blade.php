@extends('layout.main')
@section('content')
    <x-page-breadcrumb current-page='Student Courses' sub-menu='Courses' />
    <div class="row">
        <div class="col-md-12">
            <h5 class="card-title">Student Courses</h5>
        </div>
        <div class="col-md-12">
            <div class="row">
                    @foreach($courses as $course)
                        <div class="col-md-4">
                            <div class="card">
                                <img src="{{ ($course->image != '' && $course->image != null) ? asset('images/'.$course->image) : asset('images/default.jpg') }}" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $course->name }}</h5>
                                    <p class="card-text">{{ $course->description }}</p>
                                </div>
                                {{-- <ul class="list-group list-group-flush">
                                    <li class="list-group-item">Cras justo odio</li>
                                    <li class="list-group-item">Dapibus ac facilisis in</li>
                                    <li class="list-group-item">Vestibulum at eros</li>
                                </ul> --}}
                                <div class="card-body text-center">	
                                    <a href="javascript:void(0)" class="card-link btn btn-primary btn-sm">View Course</a>
                                    <a href="javascript:void(0)" class="card-link btn btn-success btn-sm">Enroll Now</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
@section('css')
    @parent
    <style type="text/css">

    </style>
@endsection
@section('scripts')
    @parent
    <script type="text/javascript">
        
    </script>
@endsection
