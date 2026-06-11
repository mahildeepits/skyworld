@extends('layout.main')
@section('content')
@php
$user = authUser();
@endphp
    <div class="container-fluid py-4">
        <x-page-breadcrumb :currentPage="'Lock Wallet'" />
        
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card bg-main text-white h-100 position-relative overflow-hidden border-0 shadow-lg" style="border-radius: 24px;">
                    <div class="card-body p-4 position-relative" style="z-index: 1;">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h6 class="text-white-50 text-uppercase small font-weight-bold mb-1">Total Assets</h6>
                                <h2 class="text-white font-weight-bold mb-0">{{ $user->walletIncomesByKey() ?? 0.00 }} <span class="small font-weight-normal text-white-50">USDT</span></h2>
                            </div>
                            <div class="bg-white-20 p-3 rounded-circle" style="background: rgba(255,255,255,0.2);">
                                <i class="icon-wallet m-0"></i>
                            </div>
                        </div>
                        
                        <div class="row mt-4 pt-2">
                            <div class="col-4 border-right border-white-10">
                                <p class="text-white-50 small mb-1">Locking</p>
                                <h5 class="text-white font-weight-bold">{{ number_format($user->stakings->sum('amount'),2) ?? 0.00 }}</h5>
                            </div>
                            <div class="col-4 border-right border-white-10">
                                <p class="text-white-50 small mb-1">Total Profit</p>
                                <h5 class="text-white font-weight-bold">{{ number_format($user->stakings->sum('return_amount'),2) ?? 0.00 }}</h5>
                            </div>
                            <div class="col-4">
                                <p class="text-white-50 small mb-1">Total Lockings</p>
                                <h5 class="text-white font-weight-bold">{{ $user->stakings->count() ?? 0 }}</h5>
                            </div>
                        </div>
                    </div>
                    <!-- Decorative Circle -->
                    <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-0 shadow-sm" style="border-radius: 24px;">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4">Locked IN</h5>
                        <form action="{{route('member.locking')}}" method='post' onsubmit="ajaxFormSubmit($(this))">
                            @csrf
                            <div class="form-group mb-4">
                                <label class="font-weight-bold small text-muted text-uppercase mb-2">Amount to Lock</label>
                                <div class="input-group input-group-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text font-weight-bold border-0 bg-light" style="border-radius: 12px 0 0 12px; padding: 0rem 0.75rem;">$</span>
                                    </div>
                                    <input type="number" name="amount" value="100" class="form-control" id="amount" readonly style="border-radius: 0 12px 12px 0; background: #e9ecef; cursor: not-allowed; pointer-events: none;">
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <small class="text-muted">Available: <span class="font-weight-bold text-dark">{{ $user->walletIncomesByKey() ?? 0.00 }} USDT</span></small>
                                </div>
                            </div>
                            
                            <div class="form-group mb-4">
                                <label class="font-weight-bold small text-muted text-uppercase mb-2">Lock Duration</label>
                                <select name="duration" class="form-control form-control-lg custom-select" style="border-radius: 12px; height: calc(2.875rem + 2px);" required>
                                    <option value="1">1 Year - Profit: $10,000</option>
                                    <option value="3">3 Years - Profit: $30,000</option>
                                    <option value="5">5 Years - Profit: $60,000</option>
                                    <option value="7">7 Years - Profit: $70,000</option>
                                </select>
                            </div>
                            
                            <button class="btn btn-main btn-lg btn-block py-3 font-weight-bold" type='submit' style="border-radius: 12px;">
                                <i class="icon-login mr-2"></i> LOCKED IN
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm" style="border-radius: 24px;">
                    <div class="card-header bg-white border-0 pt-4 pb-0">
                        <h5 class="mb-0 font-weight-bold ml-2">Locking Records</h5>
                    </div>
                    <div class="card-body p-4">
                        @if($user->stakings->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Lock Date</th>
                                            <th>Unlock Time</th>
                                            <th>Remaining</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user->stakings as $lockedStake)    
                                            <tr class="parent">
                                                <td>{{ \Carbon\Carbon::parse($lockedStake->created_at)->format('d M, Y H:i') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($lockedStake->locked_for)->format('d M, Y H:i') }}</td>
                                                <td>
                                                    @if($lockedStake->is_unlocked == 1)
                                                        <span class="badge badge-success px-3 py-2 rounded-pill">Redeemed</span>
                                                    @else
                                                        <span class="badge badge-warning-light text-warning px-3 py-2 rounded-pill font-weight-bold time_interval" data-time="{{$lockedStake->locked_for ?? now() }}" data-redeem-url="{{ route('unlock.stake', encrypt($lockedStake->id)) }}">
                                                            --
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="font-weight-bold amount" data-amount="{{ $lockedStake->amount }}">{{ number_format($lockedStake->amount, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <img src="{{ asset('images/empty-box.png') }}" style="width: 80px; opacity: 0.5;" class="mb-3">
                                <h6 class="text-muted">No Locking Records Found</h6>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
@section('scripts')
    <script>
        function updateTimeDifference(element) {
            const now = new Date();
            const futureDateTime = new Date(element.data('time'));
            let diff = futureDateTime - now;

            if (diff < 0) {
                let redeemUrl = element.data('redeem-url');
                let token = '{{ csrf_token() }}';
                element.parent().html(`<button class="btn btn-sm btn-success rounded-pill px-3 font-weight-bold" onclick="ajaxOnClick('${redeemUrl}', 'post', {'_token': '${token}'})" type="button">REDEEM</button>`);
                return;
            }

            const seconds = Math.floor((diff / 1000) % 60);
            const minutes = Math.floor((diff / 1000 / 60) % 60);
            const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
            const totalDays = Math.floor(diff / (1000 * 60 * 60 * 24));
            
            const years = Math.floor(totalDays / 365);
            const days = totalDays % 365;

            let formatted = '';
            if (years > 0) {
                formatted += `${years} Years `;
            }
            formatted += `${days} Days ${pad(hours)} Hours ${pad(minutes)} Minutes ${pad(seconds)} Seconds`;
            
            element.text(formatted);
        }

        // Pad single digit values with leading zero
        function pad(num) {
            return num.toString().padStart(2, '0');
        }

        // Initial call and then run every 1 second
        
    $(document).ready(function () {
        setInterval(() => { 
            $('.time_interval').each(function () {
                updateTimeDifference($(this));
            });
        }, 1000);
    });
</script>
@endsection