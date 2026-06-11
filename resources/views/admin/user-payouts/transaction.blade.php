{!! Form::open(['method' => 'post','route' => ['admin.pay.payouts',$id],'onsubmit="ajaxFormSubmit($(this))"']) !!}
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="p-2 bg-light rounded border">
                <p class="mb-1"><strong>Name:</strong> {{ $payout->user->name ?? 'N/A' }} ({{ $payout->user->member_id ?? '' }})</p>
                <p class="mb-1"><strong>Mobile:</strong> {{ $payout->user->mobile ?? 'N/A' }}</p>
                <p class="mb-0"><strong>Wallet Address (BEP-20):</strong></p>
                <p class="font-weight-bold text-break mb-0" style="color: #00e2fb; white-space: pre-line; word-break: break-word; font-size: 1.1rem;">{{ $payout->wallet_address }}</p>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="transaction_hash">Transaction ID (Unique ID)</label>
                <input type="text" name="transaction_hash" id="transaction_hash" class="form-control" value="" placeholder="Enter transaction ID here" >
                <div class="invalid-feedback"></div>
            </div>
        </div>
        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-block py-2 font-weight-bold shadow-sm" style="background-color: #00e2fb; color: #07122e; border: none; transition: all 0.3s ease;">Approve Withdrawal & Save ID</button>
        </div>
    </div>
{!! Form::close() !!}