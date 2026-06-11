@extends('layout.main')
@section('content')
@php
$user = authUser();
@endphp
    <x-page-breadcrumb :currentPage="'News & Events'" />
    <div class="row">
            @if(isset($news_events) && $news_events->count() > 0)
                @foreach ($news_events as $news_event)
                <div class="col-md-4">
                        <div class="card" style="border-radius:10px;">
                            <img class="card-img-top" src="{{$news_event->image_path ?? '' }}" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title">{{ $news_event->title }}</h5>
                                <p class="card-text">{{ $news_event->description }}</p>
                                <span class="float-end text-muted" style="font-size: 10px;">{{ $news_event->created_at->toDayDateTimeString() ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
    </div>@endsection
