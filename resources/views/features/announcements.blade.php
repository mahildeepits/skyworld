@extends('layout.main')
@section('content')
@php
$user = authUser();
@endphp
    <x-page-breadcrumb :currentPage="'Announcements'" />
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-4">
            @if(isset($announcements) && $announcements->count() > 0)
                @foreach ($announcements as $announcement)
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0" style="border-radius:12px; overflow:hidden;">
                            @if($announcement->image != null)
                                <div class="w-100 bg-light d-flex align-items-center justify-content-center p-2 border-bottom" style="height: 220px;">
                                    <img src="{{$announcement->image_path ?? '' }}" alt="Announcement Image" class="img-fluid" style="max-height: 100%; object-fit: contain; border-radius: 8px;">
                                </div>
                            @endif
                            
                            <div class="card-body d-flex flex-column">
                                @if(!empty($announcement->title))
                                    <h6 class="card-title fw-bold text-primary mb-2">
                                        <i class="bx bxs-volume-full me-1 align-middle"></i>{{ ucwords($announcement->title) }}
                                    </h6>
                                @endif
                                
                                @if(!empty($announcement->description))
                                    <p class="card-text flex-grow-1 text-secondary mb-3" style="font-size: 14px;">
                                        {{ ucfirst($announcement->description) }}
                                    </p>
                                @endif
                                
                                @if(empty($announcement->title) && empty($announcement->description))
                                    <div class="flex-grow-1"></div>
                                @endif
                                
                                <div class="mt-auto d-flex justify-content-end pt-2 {{ (!empty($announcement->title) || !empty($announcement->description)) ? 'border-top' : '' }}">
                                    <span class="text-muted fw-medium" style="font-size: 11px;">
                                        <i class="bx bx-time-five align-middle"></i> {{ $announcement->created_at ? $announcement->created_at->toDayDateTimeString() : 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
    </div>@endsection
