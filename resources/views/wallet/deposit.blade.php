@extends('layout.main')
@section('content')
    <x-page-breadcrumb current-page='Deposit' subMenu='Wallet' />
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <!-- <div class="row mb-3">
                        <div class="col-md-12 text-end">
                            <a href="{{ route('track.deposit.cron') }}"
                               class="btn btn-main btn-sm text-white"
                               onclick="return confirm('Are you sure? You want to track deposits')">
                                Track Deposits
                            </a>
                        </div>
                    </div> -->
                    <div style="max-width: 500px; margin: 0 auto;">
                        <!-- <div class="alert alert-warning text-center" style="border-radius: 10px;">
                            <h5 class="mb-2"><i class="bx bxs-error-circle"></i> IMPORTANT NOTICE</h5>
                            <p class="mb-1" style="font-size: 14px;">We recommend sending funds from your registered wallet address:</p>
                            <p class="mb-2" style="word-break: break-all; font-weight: bold; color: #d9534f;">{{ getWalletAddress(authUser(), $type) ?: 'No address registered' }}</p>
                            <small class="d-block" style="font-size: 12px;">If you use a different address, please specify it below during submission to ensure tracking.</small>
                        </div> -->
                        <form action="{{route('wallet.deposit')}}" method="post" enctype="multipart/form-data" onsubmit="ajaxFormSubmit($(this))">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group my-2">
                                        <select name="wallet_type" id="wallet_type" class="form-select form-control" >
                                            <option value="crypto"> Crypto </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group my-2">
                                        <select name="currency" id="currency" class="form-select form-control" >
                                            <option value="USDT"> USDT </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group my-2">
                                        <select name="type" id="type" class="form-control form-select">
                                            <option value="BEP-20" selected>BEP-20</option>
                                        </select>
                                        <!-- <input type="hidden" name="type" value="BEP-20"> -->
                                    </div>
                                </div>
                                <div class="col-12 mt-2" >
                                    <ol class="px-3 m-0" style="font-weight:500; padding-inline-start: 0;">
                                        <li><p class="m-0 pt-1"> Buy USDT on Coinbase, Binance or other exchange</p></li>
                                        <li><p class="m-0 pt-1"> Send/Withdrawl USDT to the address with the network below.</p></li>
                                        <!-- <li><p class="m-0 pt-1"> Submit your sender wallet address below to track your deposit.</p></li> -->
                                    </ol>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="form-group">
                                        <label for="wallet_address" class="my-2">Your USDT Deposit Address <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" name="wallet_address" value="{{ $address }}" class="form-control" id="wallet_address" readonly>
                                            <button class="btn btn-main text-white" style="border-left:none;" type="button" id="copyWalletBtn" title="Copy to clipboard">
                                                 <img src="{{asset('images/copy-icon.png')}}" alt="" style="width: 20px;">
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 text-center my-3">
                                    <div style="width:fit-content; background:#fff; border-radius: 10px; padding: 10px; margin: 0 auto; box-shadow: 0px 0px 10px rgba(0,0,0,0.3);">
                                        {!! $qrCode !!}
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-2">
                                        <label for="paid_from_address" class="my-2">Wallet Address You Paid From <span class="text-danger">*</span></label>
                                        <input type="text" name="paid_from_address" id="paid_from_address" class="form-control" placeholder="Enter the wallet address you sent funds from" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-2">
                                        <label for="transaction_hash" class="my-2">Transaction Hash / TxID (Optional)</label>
                                        <input type="text" name="transaction_hash" id="transaction_hash" class="form-control" placeholder="Enter transaction hash (TxID) if available">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label for="receipt" class="my-2">Upload Deposit Screenshot / Receipt Proof <span class="text-danger">*</span></label>
                                        <input type="file" name="receipt" id="receipt" class="form-control" accept="image/*" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-12 mt-3">
                                    <button class="btn btn-main text-white w-100 py-2" type="submit">Submit Deposit Proof</button>
                                </div>
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        $('#copyWalletBtn').on('click', function () {
            const walletInput = document.getElementById("wallet_address");
            walletInput.select();
            walletInput.setSelectionRange(0, 99999); // For mobile devices
            navigator.clipboard.writeText(walletInput.value).then(() => {
                alert("Wallet address copied to clipboard!");
            });
        });
    </script>
@endsection
