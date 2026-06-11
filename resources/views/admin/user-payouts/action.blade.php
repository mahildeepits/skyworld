@if ($model->status == 'pending' || $model->status == 'Pending')
    <a href="javascript:void(0)" onclick="commanModel(`{{route('admin.pay.view',encrypt($model->id))}}`,'Pay To User')" class="btn btn-warning btn-sm">Pay</a>
    <button type="button" onclick="if(confirm('Are you sure you want to REJECT this withdrawal? The amount will be REFUNDED to the user.')) ajaxOnClick(`{{route('admin.reject.payouts',encrypt($model->id))}}`,'get')" class="btn btn-danger btn-sm">Reject</button>
@endif