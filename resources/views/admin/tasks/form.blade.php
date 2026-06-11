@php
$route = 'tasks.store';
$method = 'POST';
    if(isset($task)){
        $route = ['tasks.update',encrypt($task->id)];
        $method = 'PUT';
    }
$orderData = generateRandomOrderData();
$oldReviews = $task?->reviews ?? [];
@endphp
{!! Form::open(['route'=> $route,'files'=>true,'method' => 'POST','onsubmit="ajaxFormSubmit($(this))"']) !!}
    @method($method)
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('title','Title') !!} <span class="text-danger">*</span>
                {!! Form::text('title',$task?->title ?? null,['class'=>'form-control','id' => 'title']) !!}
                <div class="invalid-feedback"></div>
            </div>
        </div>
        <div class="col-md-12 ">
            <div class="form-group">
                {!! Form::label('title_description','Title Description') !!}<span class="text-danger">*</span>
                {!! Form::text('title_description',$task?->title_description ?? null,['class'=>'form-control','id' => 'title_description']) !!}
                <div class="invalid-feedback"></div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('product_type','Product Title') !!}<span class="text-danger">*</span>
                {!! Form::text('product_type',$task?->product_type ?? null,['class'=>'form-control','id' => 'product_type']) !!}
                <div class="invalid-feedback"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('order_number','Order Number') !!}<span class="text-danger">*</span>
                {!! Form::text('order_number',$task?->order_number ?? $orderData['order_no'] ?? null,['class'=>'form-control','id' => 'order_number']) !!}
                <div class="invalid-feedback"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('order_date','Order Date') !!}<span class="text-danger">*</span>
                {!! Form::date('order_date',$task?->order_date ?? $orderData['order_date'] ?? null,['class'=>'form-control','id' => 'order_date']) !!}
                <div class="invalid-feedback"></div>
            </div>
        </div>
        <div class="col-md-12">
            <label for="reviews">Reviews</label>
            <div id="reviews-wrapper">
                @foreach($oldReviews as $review)
                    <div class="input-group mb-2 review-item">
                        <input type="text" name="reviews[]" class="form-control" value="{{ $review }}" placeholder="Enter review">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-danger remove-review">&times;</button>
                        </div>
                    </div>
                @endforeach
                @if(empty($oldReviews))
                    <div class="input-group mb-2 review-item">
                        <input type="text" name="reviews[]" class="form-control" placeholder="Enter review">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-danger remove-review">&times;</button>
                        </div>
                    </div>
                @endif
            </div>
            <button type="button" class="btn btn-sm btn-main mt-1" id="add-review">+ Add Review</button>
        </div>
        <div class="col-md-12 pt-1">
            {!! Form::submit('Save',['class'=>'btn btn-main']) !!}
        </div>
    </div>
{!! Form::close() !!}
<script>
$(document).ready(function () {
    // Add new review input
    $('#add-review').click(function () {
        const reviewInput = `
            <div class="input-group mb-2 review-item">
                <input type="text" name="reviews[]" class="form-control" placeholder="Enter review">
                <div class="input-group-append">
                    <button type="button" class="btn btn-danger remove-review">&times;</button>
                </div>
            </div>`;
        $('#reviews-wrapper').append(reviewInput);
    });

    // Remove review input
    $(document).on('click', '.remove-review', function () {
        $(this).closest('.review-item').remove();
    });
});
</script>