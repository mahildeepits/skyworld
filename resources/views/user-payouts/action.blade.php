@if($model->income_type == 'level' && $model->net_amount >= 10 && $model->is_requested == null && $model->is_paid == null)
   <a href="{{route('member.request.payout',encrypt($model->id))}}" class="btn btn-primary btn-sm">Pay Request</a>
@else
    @if ($model->income_type != 'level' && $model->is_requested == null && $model->is_paid == null)
        <a href="{{route('member.request.payout',encrypt($model->id))}}" onclick="return confirm('Are you sure to make a request for this payout ? ')" class="btn btn-primary btn-sm">Pay Request</a>
    @elseif($model->is_paid != null)
        <a href="javascript:void(0)" class="btn btn-success btn-sm">Paid</a>
    @elseif($model->is_requested != null)
        <a href="javascript:void(0)" class="btn btn-warning btn-sm">Pending</a>
    @endif
@endif