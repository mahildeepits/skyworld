@extends('layout.main')
@section('content')
@php
$orderData = generateRandomOrderData();
@endphp
<style>
    .border-content {
        background: rgba(28, 28, 28, 0.4);
        border-radius: 5px;
        min-width: 325px;
        max-width: 80%;
        margin: 0px auto;
    }
    .star-rating {
        direction: rtl;
        font-size: 2rem;
        unicode-bidi: bidi-override;
        display: flex;
        justify-content: end;
        gap: 5px;
    }
    .star-rating input[type="radio"] {
        display: none;
    }
    .star-rating label {
        color: #ccc;
        cursor: pointer;
    }
    .star-rating input[type="radio"]:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: gold;
    }
</style>
    <div class="container-fluid py-2">
        <x-page-breadcrumb :currentPage="'Task'" />
        <form action="{{route('complete.task')}}" method="post" onsubmit="ajaxFormSubmit($(this))" >
            @csrf
            <input type="hidden" name="task_id" value="{{ $task->id ?? null }}">
            <input type="hidden" name="redirectional_url" value="{{ route('explore.category',encrypt($category->id)) ?? null }}">
            <input type="hidden" name="category_id" value="{{ $category->id ?? null }}" />
            <input type="hidden" name="per_task_amount" value="{{ $perTaskAmount ?? 0 }}" />
            <div class="row">
                <div class="col-md-12">
                    <div class="card mx-2 card-slider " style="background-color:transparent;box-shadow:none;border-radius: 30px 30px 0px 0px;">
                        <div class="card-body p-0">
                            <div class="py-4 px-3 bg-main" style="color: white!important;color: white;border-radius: 30px 30px 0px 0px;">
                                <div class="bg-muted text-center mb-2" width="100%" height="20%">
                                    <h1 class="text-white"><b>{{ $task->title ?? '' }}</b></h1>
                                    <p class="m-0" style="font-size:12px;" >{{ $task->title_description ?? '' }}</p>
                                </div>
                                <div class="bg-muted text-center border-content" width="100%" height="20%" style="padding: 10px;">
                                    <h4 class="text-white"><b>{{ $task->product_type ?? '' }}</b></h4>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <p class="m-0 p-0 text-nowrap" style="font-size:10px;" >ORDER NO.: {{ $task->order_number ?? $orderData['order_no'] }}</p>
                                        <p class="m-0 p-0 text-nowrap" style="font-size:10px;" >ORDER DATE: {{ $task->order_date ?? $orderData['order_date'] }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4" style="border:1px solid #dddddd;">
                                <div class="text-center"><h6>Write a Review</h6></div>
                                <div class="form-group mb-2">
                                    <label for="rating"></label>Rate Your Recent Experience</label>
                                    <div class="star-rating" id="rating">
                                        <input type="radio" id="star5" name="rating" value="5"><label for="star5">&#9733;</label>
                                        <input type="radio" id="star4" name="rating" value="4"><label for="star4">&#9733;</label>
                                        <input type="radio" id="star3" name="rating" value="3"><label for="star3">&#9733;</label>
                                        <input type="radio" id="star2" name="rating" value="2"><label for="star2">&#9733;</label>
                                        <input type="radio" id="star1" name="rating" value="1"><label for="star1">&#9733;</label>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group">
                                    <label for="review" class="mb-2">Tell Us More About Your Experience</label>
                                    <textarea class="form-control" name="review" id="review" rows="3" placeholder="Enter your review here"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div>
                                     @if (!empty($task->reviews) && is_array($task->reviews))
                                    <div class="mt-2">
                                        <label class="mb-1" style="font-weight:bold;">Suggestions:</label>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($task->reviews as $suggestion)
                                                <span class="suggestion-badge badge bg-secondary text-white px-2 py-1" style="cursor:pointer;">{{ $suggestion }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="form-group mt-3">
                                    <input type="submit" value="Submit Review" class="btn bg-main text-white btn-sm" style="border:none;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const suggestionBadges = document.querySelectorAll('.suggestion-badge');
        const reviewTextarea = document.getElementById('review');

        suggestionBadges.forEach(badge => {
            badge.addEventListener('click', function () {
                // Remove active styling from other suggestions
                suggestionBadges.forEach(b => b.classList.remove('bg-primary'));
                // Set new value to textarea
                reviewTextarea.value = this.textContent;
                // Highlight selected badge
                this.classList.add('bg-primary');
            });
        });
        // document.getElementById('star5').checked = true; // Default to 5 stars
    });
</script>
@endsection
