@extends('layout.main')

@section('css')
<style>
    /* ── Header & Summary ── */
    .wallet-header-card {
        background: #04c;
        border-radius: 20px !important;
        padding: 30px !important;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 25px;
        box-shadow: 0 8px 32px rgba(3,75,179,0.3) !important;
    }
    .wallet-header-card::before {
        content: ''; position: absolute; top: -20%; right: -10%;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(0,226,251,0.1) 0%, transparent 70%);
    }
    .wallet-label { font-size: 0.8rem; opacity: 0.7; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; }
    .wallet-balance { font-size: 2.2rem; font-weight: 800; margin: 5px 0 15px; letter-spacing: -0.5px; }
    
    .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .stat-pill {
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.12);
        padding: 10px 15px; border-radius: 12px;
        backdrop-filter: blur(5px);
    }
    .stat-pill small { display: block; font-size: 0.65rem; opacity: 0.7; text-transform: uppercase; margin-bottom: 2px; }
    .stat-pill span { font-weight: 700; font-size: 0.95rem; }

    /* ── Transaction List ── */
    .tx-section-title { font-size: 1.1rem; font-weight: 700; color: #0f172a; margin: 10px 0 20px; display: flex; align-items: center; gap: 10px; }
    
    .tx-card {
        background: #fff;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        border: 1px solid #f1f5f9;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .tx-card:active { transform: scale(0.98); }
    
    .tx-icon {
        width: 45px; height: 45px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; flex-shrink: 0;
    }
    .tx-icon.income { background: rgba(34,197,94,0.1); color: #22c55e; }
    .tx-icon.deposit { background: rgba(3,75,179,0.1); color: #034bb3; }
    .tx-icon.withdraw { background: rgba(239,68,68,0.1); color: #ef4444; }
    .tx-icon.transfer { background: rgba(245,158,11,0.1); color: #f59e0b; }
    
    .tx-info { flex-grow: 1; padding-left: 14px; min-width: 0; }
    .tx-category { font-weight: 700; font-size: 0.95rem; color: #1e293b; margin-bottom: 2px; }
    .tx-date { font-size: 0.72rem; color: #94a3b8; }
    
    .tx-amount-side { text-align: right; flex-shrink: 0; }
    .tx-amount { font-weight: 800; font-size: 1.05rem; margin-bottom: 2px; }
    .tx-status { font-size: 0.65rem; font-weight: 700; padding: 3px 8px; border-radius: 6px; text-transform: uppercase; }
    .status-Completed { background: #dcfce7; color: #166534; }
    .status-Pending { background: #fef9c3; color: #854d0e; }
    .status-Failed { background: #fee2e2; color: #991b1b; }
    .status-Rejected { background: #fee2e2; color: #991b1b; }
    .tx-details {
        margin-top: 12px; padding-top: 12px;
        border-top: 1px dashed #e2e8f0;
        font-size: 0.75rem; color: #64748b;
    }
    .tx-hash-box {
        background: #f8fafc; padding: 8px; border-radius: 8px;
        margin-top: 6px; font-family: monospace;
        word-break: break-all; color: #034bb3;
        border: 1px solid #edf2f7;
    }

    /* ── Pagination Custom ── */
    #custom-pagination {
        display: flex; justify-content: center; align-items: center;
        gap: 8px; margin-top: 25px; padding-bottom: 20px;
    }
    .pg-btn {
        width: 36px; height: 36px;
        border-radius: 10px; border: 1px solid #e2e8f0;
        background: #fff; color: #64748b;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.2s;
    }
    .pg-btn:hover:not(:disabled) { background: #034bb3; color: #fff; border-color: #034bb3; }
    .pg-btn:disabled { opacity: 0.4; cursor: not-allowed; }
    .pg-info { font-size: 0.8rem; font-weight: 600; color: #64748b; margin: 0 10px; }

    /* Mobile specifically */
    @media (max-width: 576px) {
        .wallet-balance { font-size: 1.8rem; }
        .tx-card { padding: 14px; }
    }
</style>
@endsection

@section('content')
<x-page-breadcrumb current-page='Wallet' sub-menu='Transactions' />

<div class="container-fluid p-2 p-md-4">
    
    {{-- ═══════════════ WALLET SUMMARY ═══════════════ --}}
    <div class="wallet-header-card">
        <div class="row align-items-center">
            <div class="col-12 col-md-6 mb-4 mb-md-0">
                <p class="wallet-label mb-1">Available Balance</p>
                <h1 class="wallet-balance">${{ number_format(authUser()->walletIncomesByKey('totalIncome'), 2) }}</h1>
                <!-- <div class="d-flex gap-2">
                    <a href="{{ route('wallet.transfer') }}" class="btn btn-light btn-sm rounded-pill px-3 fw-bold">
                        <i class="icon-share-alt me-1"></i> Transfer
                    </a>
                    <a href="{{ route('wallet.deposit') }}" class="btn btn-outline-light btn-sm text-white rounded-pill px-3 fw-bold">
                        <i class="icon-plus me-1"></i> Deposit
                    </a>
                </div> -->
            </div>
            <div class="col-12 col-md-6">
                <div class="stats-grid">
                    <div class="stat-pill">
                        <small>Total Income</small>
                        <span class="text-white">${{ number_format(authUser()->walletIncomesByKey('myIncome'), 2) }}</span>
                    </div>
                    <div class="stat-pill">
                        <small>Total Deposits</small>
                        <span class="text-white text-info text-success-light">${{ number_format(authUser()->walletIncomesByKey('deposits'), 2) }}</span>
                    </div>
                    <div class="stat-pill">
                        <small>Total Withdrawals</small>
                        <span class="text-white">${{ number_format(authUser()->walletIncomesByKey('withdrawls'), 2) }}</span>
                    </div>
                    <div class="stat-pill">
                        <small>Total Records</small>
                        <span class="text-white">{{ $userTransations->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════ TRANSACTION LIST ═══════════════ --}}
    <h5 class="tx-section-title">
        <i class="icon-list text-main"></i> Transaction History
    </h5>

    <div id="transactions-container">
        @forelse($userTransations as $tx)
            @php
                $isDebit = $tx->transaction_type === 'Debit';
                $sign = $isDebit ? '-' : '+';
                $color = $isDebit ? 'text-danger' : 'text-success';
                $icon = 'icon-credit-card';
                $iconClass = 'income';
                
                $cat = strtolower($tx->category);
                if(str_contains($cat, 'deposit')) { $icon='icon-plus'; $iconClass='deposit'; }
                elseif(str_contains($cat, 'withdraw')) { $icon='icon-wallet'; $iconClass='withdraw'; }
                elseif(str_contains($cat, 'transfer')) { $icon='icon-shuffle'; $iconClass='transfer'; }
                elseif(str_contains($cat, 'income') || str_contains($cat, 'commission')) { $icon='icon-arrow-down-circle'; $iconClass='income'; }

                // Map Team Profit / Team Income to IB Income for display
                $displayCategory = ucwords(str_replace('_', ' ', $tx->category));
                $displayCategory = str_ireplace(['Team Profit', 'Team Income'], 'IB Income', $displayCategory);
                
                $displayDescription = $tx->description;
                $displayDescription = str_ireplace(['Team Profit', 'Team Income'], 'IB Income', $displayDescription);
            @endphp
            
            <div class="tx-card" data-tx-id="{{ $tx->id }}">
                <div class="d-flex align-items-center">
                    <div class="tx-icon {{ $iconClass }}">
                        <i class="{{ $icon }}"></i>
                    </div>
                    <div class="tx-info">
                        <div class="tx-category">{{ $displayCategory }}</div>
                        <div class="tx-date text-uppercase">{{ $tx->created_at->format('d M Y • h:i A') }}</div>
                    </div>
                    <div class="tx-amount-side">
                        <div class="tx-amount {{ $color }}">{{ $sign }} ${{ number_format($tx->amount, 2) }}</div>
                        <span class="tx-status status-{{ $tx->status }}">{{ $tx->status }}</span>
                    </div>
                </div>
                
                <div class="tx-details">
                    <div class="mb-1"><i class="icon-info me-1"></i> {{ $displayDescription }}</div>
                    @if($tx->tx_hash)
                        <div class="small fw-bold mt-2 mb-1">TX HASH</div>
                        <div class="tx-hash-box">
                            {{ $tx->tx_hash }}
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <img src="{{ asset('images/no-data.png') }}" style="width: 120px; opacity: 0.5;">
                <p class="text-muted mt-3">No transactions found yet.</p>
            </div>
        @endforelse
    </div>

    {{-- ═══════════════ PAGINATION ═══════════════ --}}
    @if($userTransations->count() > 10)
    <div id="custom-pagination">
        <button class="pg-btn" id="prevPage"><i class="icon-arrow-left"></i></button>
        <span class="pg-info" id="pageInfo">Page 1 of 1</span>
        <button class="pg-btn" id="nextPage"><i class="icon-arrow-right"></i></button>
    </div>
    @endif

</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let itemsPerPage = 10;
        let txCards = Array.from(document.querySelectorAll('.tx-card'));
        let currentPage = 1;
        let totalPages = Math.ceil(txCards.length / itemsPerPage);

        function showPage(page) {
            currentPage = page;
            let start = (page - 1) * itemsPerPage;
            let end = start + itemsPerPage;

            txCards.forEach((card, index) => {
                if (index >= start && index < end) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            const pageInfo = document.getElementById('pageInfo');
            if(pageInfo) pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
            
            const prev = document.getElementById('prevPage');
            const next = document.getElementById('nextPage');
            if(prev) prev.disabled = (currentPage === 1);
            if(next) next.disabled = (currentPage === totalPages || totalPages === 0);
        }

        if(txCards.length > 0) {
            showPage(1);
        }

        document.getElementById('prevPage')?.addEventListener('click', () => {
            if (currentPage > 1) showPage(currentPage - 1);
        });

        document.getElementById('nextPage')?.addEventListener('click', () => {
            if (currentPage < totalPages) showPage(currentPage + 1);
        });
    });
</script>
@endsection
