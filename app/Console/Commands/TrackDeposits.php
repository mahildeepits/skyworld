<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use App\Models\UserPayout;
use App\Models\DepositsTracking;
use App\Models\TransactionTracking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\AgentCategory;
use App\Models\UserAgentCategory;
use App\Helpers\RewardHelper;


class TrackDeposits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'track:deposits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Track deposits from BEP-20 wallets automatically';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('Transactions tracking is started.');
        $this->processDeposits('BEP-20');
        $this->processDeposits('TRC-20');
        $this->line('Transactions are tracked successfully.');
    }

    public function processDeposits($network)
    {
        Log::info("=========================== $network D E P O S I T   T R A C K I N G - ".  date('Y-m-d H:i:s') ." ============================");
        
        $contractAddress = ($network == 'BEP-20') 
            ? '0x55d398326f99059fF775485246999027B3197955' 
            : 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t';

        $companyAddress = ($network == 'BEP-20') 
            ? env('BEP_20_ADDRESS') 
            : env('TRC_20_ADDRESS');
        
        if (!$companyAddress) {
            Log::error("Admin $network wallet address not found in env.");
            return;
        }
        
        // 1️⃣ Get all users (excluding company)
        $rawUsers = User::where('is_blocked', 0)
                ->whereNotNull('wallet_addresses')
                ->whereNotIn('member_id', ['admin','company','Company','TOP','top'])
                ->get();

        $users = $rawUsers->pluck('wallet_addresses', 'id')->toArray();
        
        if (empty($users)) {
            Log::info("No user wallets found for tracking.");
            return;
        }

        $usersAddresses = array_filter(array_map(function ($item) use ($network) {
            if ($network == 'BEP-20') {
               return (isset($item['BEP-20']) && $item['BEP-20']) ? strtolower($item['BEP-20']) : null;
            } else {
               return (isset($item['TRC-20']) && $item['TRC-20']) ? $item['TRC-20'] : null;
            }
        }, $users));

        if (empty($usersAddresses)) {
            Log::info("No user wallet - $network found for tracking.");
            return;
        }

        // 2️⃣ Get existing transaction hashes
        $existingHashes = Transaction::pluck('transaction_hash')->map('strtolower')->toArray();
        $trackedHashes  = DepositsTracking::pluck('transaction_hash')->filter()->map('strtolower')->toArray();
        $mergedHashes   = array_filter(array_unique(array_merge($existingHashes, $trackedHashes)));
        // 3️⃣ Call Network Specific API
        if ($network == 'BEP-20') {
            $transactions = $this->bscApiResponse($contractAddress);
        } else {
            $transactions = $this->trcApiResponse($contractAddress);
        }
        if (empty($transactions)) {
            Log::info("No transactions found for $network tracking.");
            return;
        }
        $filteredTxs = $this->getFilteredTransactions($transactions, $contractAddress, $companyAddress, $usersAddresses, $mergedHashes);
        
        if (empty($filteredTxs)) {
            Log::info("No filtered transactions found for $network tracking.");
            return;
        }
        // 5️⃣ Process each valid transaction
        foreach ($filteredTxs as $tx) {
            $hash = strtolower($tx['hash']);

            $from   = trim($tx['from']);
            $to     = trim($tx['to']);
            $amount = round($tx['value'], 2);
            $time   = Carbon::createFromTimestamp($tx['timeStamp'])->format('Y-m-d H:i:s');
            
            // 6️⃣ Find which user this deposit belongs to
            $userId = null;
            foreach ($users as $id => $wallets) {
                $walletsArr = (array)$wallets;
                $userAddr = $walletsArr[$network] ?? null;
                
                if ($userAddr) {
                    $isMatch = ($network == 'BEP-20') 
                        ? (strtolower($userAddr) == strtolower($from)) 
                        : ($userAddr == $from);
                        
                    if ($isMatch) {
                        $userId = $id;
                        break;
                    }
                }
            }
            
            if (!$userId) {
                Log::info("No User ID found for this deposit - " . $from . " on " . $network);
                continue;
            }

            $user = User::find($userId);
            if (!$user) {
                Log::info("No User found for id - " . $userId);
                continue;
            }

            backupBeforeWalletAddressChange($user->id, 'track_deposit', ['transactions', 'transaction_trackings', 'deposits_trackings']);
            
            // ✅ Store deposit
            $transaction = Transaction::create([
                'user_id'          => $user->id,
                'wallet_address'   => $from,
                'type'             => 'deposit',
                'amount'           => $amount,
                'transaction_fees' => 0,
                'net_amount'       => $amount,
                'transaction_hash' => $hash,
                'status'           => 'success',
            ]);

            $transactionTracking = TransactionTracking::create([
                'user_id'     => $user->id,
                'type'        => 'deposit',
                'keyword'     => 'Transaction',
                'amount'      => $amount,
                'net_amount'  => $amount,
                'related_id'  => $transaction->id,
                'remark'      => 'Deposit Amount tracked from '. $network .' wallet: ' . $from,
            ]);

            $unified = \App\Models\UnifiedTransaction::updateOrCreate(
                [
                    'tx_hash' => $hash,
                ],
                [
                    'user_id'          => $user->id,
                    'amount'           => $amount,
                    'transaction_type' => 'Credit',
                    'category'         => 'Deposit',
                    'status'           => 'Completed',
                    'description'      => 'Deposit confirmed via '. $network .' wallet: ' . $from,
                    'created_at'       => $transactionTracking->created_at,
                ]
            );

            DepositsTracking::updateOrCreate(
                [
                    'transaction_hash' => $hash,
                ],
                [
                    'user_id'      => $user->id,
                    'confirmed_at' => now(),
                    'type'         => $network,
                ]
            );

            // ✅ Activate matching package for the user based on deposit amount
            $matchingCategory = AgentCategory::where('unlock_balance', '>=', 50)
                ->where('unlock_balance', '<=', $amount)
                ->orderBy('unlock_balance', 'desc')
                ->first();

            if ($matchingCategory) {
                $hasCategory = UserAgentCategory::where('user_id', $user->id)
                    ->where('agent_category_id', $matchingCategory->id)
                    ->exists();

                if (!$hasCategory) {
                    $eligibility = RewardHelper::checkPackageEligibility($user, $matchingCategory);
                    if ($eligibility['status']) {
                        UserAgentCategory::create([
                            'user_id'           => $user->id,
                            'agent_category_id' => $matchingCategory->id,
                        ]);
                        // Only give upgrade income if user was already paid (otherwise first-deposit tiered income applies)
                        // if ($user->is_paid == 1) {
                        //     RewardHelper::packageUpgradeDirectIncome($user, $matchingCategory);
                        // }
                    }
                }
            }

            // ✅ Set user status to paid if it's their first deposit or they are currently unpaid
            if ($user->is_paid == 0) {
                // Determine direct income amount for sponsor based on new tiers
                $directIncomeAmount = 0;
                if ($amount >= 300) {
                    $directIncomeAmount = 30;
                } elseif ($amount >= 200) {
                    $directIncomeAmount = 25;
                } elseif ($amount >= 100) {
                    $directIncomeAmount = 15;
                } elseif ($amount >= 50) {
                    $directIncomeAmount = 7;
                }

                if ($directIncomeAmount > 0 && $user->sponsor_id) {
                    $sponsor = User::where('member_id', $user->sponsor_id)->first();
                    if ($sponsor) {
                        $payout = UserPayout::create([
                            'user_id'       => $sponsor->id,
                            'income_type'   => 'direct_income',
                            'amount'        => $directIncomeAmount,
                            'tds'           => 0,
                            'admin_charges' => 0,
                            'net_amount'    => $directIncomeAmount,
                        ]);

                        $payoutTracking = TransactionTracking::create([
                            'user_id'     => $sponsor->id,
                            'type'        => 'direct_income',
                            'keyword'     => 'Reward',
                            'amount'      => $directIncomeAmount,
                            'net_amount'  => $directIncomeAmount,
                            'related_id'  => $payout->id,
                            'from_user'   => $user->id,
                            'remark'      => "Direct Income from {$user->member_id}'s first deposit of \${$amount} via $network",
                        ]);

                        \App\Models\UnifiedTransaction::updateOrCreate(
                            [
                                'user_id'          => $sponsor->id,
                                'from_user_id'     => $user->id,
                                'category'         => 'Direct Income',
                                'amount'           => $directIncomeAmount, 
                                'transaction_type' => 'Credit',
                            ],
                            [
                                'status'      => 'Completed',
                                'description' => "Direct Income from {$user->member_id}'s first deposit of \${$amount} via $network",
                                'created_at'  => $payoutTracking->created_at,
                            ]
                        );

                        // Trigger Team Profit Income for the receiver's uplines
                        // RewardHelper::processTeamProfitIncome($sponsor, $directIncomeAmount, 'Direct Income');
                    }
                }

                // ✅ 5% Cashback for the user on first deposit
                $cashbackAmount = round($amount * 0.05, 2);
                if ($cashbackAmount > 0) {
                    $cashbackPayout = UserPayout::create([
                        'user_id'       => $user->id,
                        'income_type'   => 'first_deposit_cashback',
                        'amount'        => $cashbackAmount,
                        'tds'           => 0,
                        'admin_charges' => 0,
                        'net_amount'    => $cashbackAmount,
                    ]);

                    $cashbackTracking = TransactionTracking::create([
                        'user_id'     => $user->id,
                        'type'        => 'first_deposit_cashback',
                        'keyword'     => 'Reward',
                        'amount'      => $cashbackAmount,
                        'net_amount'  => $cashbackAmount,
                        'related_id'  => $cashbackPayout->id,
                        'remark'      => "5% First Deposit Cashback bonus on \${$amount} deposit",
                    ]);

                    \App\Models\UnifiedTransaction::create([
                        'user_id'          => $user->id,
                        'amount'           => $cashbackAmount,
                        'transaction_type' => 'Credit',
                        'category'         => 'Bonus Income',
                        'status'           => 'Completed',
                        'description'      => "5% First Deposit Cashback bonus on \${$amount} deposit via $network",
                        'created_at'       => $cashbackTracking->created_at,
                    ]);
                    
                    Log::info("✅ First Deposit Cashback of \${$cashbackAmount} credited for User {$user->member_id}");
                }

                $user->update([
                    'is_paid' => 1,
                    'user_icon' => 'userpaid.png',
                    'email_verified_at' => $user->email_verified_at ?? now()
                ]);
            }
            
            Log::info("✅ Deposit tracked for User {$user->member_id}, Amount: {$amount}, Hash: {$hash}");
        }
    }

    public function bscApiResponse($contractAddress = null) {
        $NODE_REAL_URL = env('BEP_20_API');
        $BEP_WALLET_ADDRESS = env('BEP_20_ADDRESS');
        
        if (!$BEP_WALLET_ADDRESS) {
            Log::error("Admin BEP-20 wallet address not found in env.");
            return [];
        }

        $fromBlock = "0x0";
        try {
            $blockRes = Http::post($NODE_REAL_URL, [
                'jsonrpc' => '2.0', 'id' => 1, 'method' => 'eth_blockNumber', 'params' => []
            ]);
            if ($blockRes->successful()) {
                $currentBlock = hexdec($blockRes->json()['result'] ?? '0x0');
                // NodeReal has a 50,000 block limit for eth_getLogs. We use 45,000 for safety.
                if ($currentBlock > 45000) {
                    $fromBlock = '0x' . dechex($currentBlock - 45000);
                } else {
                    $fromBlock = "0x0";
                }
            }
        } catch (\Exception $e) {
            Log::warning("Could not fetch current block number, defaulting to latest 5000 blocks: " . $e->getMessage());
            // If block number fails, we might still fail eth_getLogs if fromBlock is 0x0. 
            // We'll proceed but it might error out with "range exceeded".
        }

        $walletTopic = '0x000000000000000000000000' . strtolower(substr($BEP_WALLET_ADDRESS, 2));
        $payload = [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'eth_getLogs',
            'params' => [
                [
                    "fromBlock" => $fromBlock,
                    "toBlock" => "latest",
                    "address" => $contractAddress,
                    "topics" => [
                        "0xddf252ad1be2c89b69c2b068fc378daa952ba7f163c4a11628f55a4df523b3ef",
                        null,
                        $walletTopic
                    ]
                ]
            ]
        ];

        try {
            $response = Http::post($NODE_REAL_URL, $payload);
            
            if ($response->failed()) {
                Log::error("NodeReal BSC API Call Failed: " . $response->body());
                return [];
            }

            $jsonData = $response->json();
            $rawTransfers = $jsonData['result'] ?? [];
            
            $transfers = collect($rawTransfers)->map(function ($tx) {
                $hexValue = $tx['data'] ?? '0x0';
                $value = hexdec($hexValue) / 1e18;
                
                return [
                    'hash' => $tx['transactionHash'] ?? '',
                    'from' => '0x' . substr($tx['topics'][1], 26) ?? '',
                    'to' => '0x' . substr($tx['topics'][2], 26) ?? '',
                    'value' => $value,
                    'tokenDecimal' => 18,
                    'contractAddress' => $tx['address'] ?? '',
                    'timeStamp' => time(),
                ];
            })->toArray();
            return $transfers;

        } catch (\Exception $e) {
            Log::error("NodeReal BSC API Error: " . $e->getMessage());
            return [];
        }
    }

    public function trcApiResponse($contractAddress = null) {
        $apiUrl = env('TRC_20_API');
        $walletAddress = env('TRC_20_ADDRESS');
        
        if (!$walletAddress) {
            Log::error("Admin TRC-20 wallet address not found in env.");
            return [];
        }

        // Standard TRC20 history query via TronGrid style endpoint (NodeReal proxy)
        try {
             $url = "{$apiUrl}/v1/accounts/{$walletAddress}/transactions/trc20";
             $response = Http::get($url, [
                 'only_confirmed' => 'true',
                 'limit' => 50,
                 'contract_address' => $contractAddress
             ]);
            
            if ($response->failed()) {
                Log::error("NodeReal Tron API Call Failed: " . $response->body());
                return [];
            }

            $jsonData = $response->json();
            $rawTransfers = $jsonData['data'] ?? [];
            
            $transfers = collect($rawTransfers)->map(function ($tx) {
                // TronGrid returns value as a string with decimals (e.g. 1000000 for 1 USDT)
                $value = floatval($tx['value'] ?? 0) / 1e6; // USDT on TRC20 uses 6 decimals
                
                return [
                    'hash' => $tx['transaction_id'] ?? '',
                    'from' => $tx['from'] ?? '',
                    'to' => $tx['to'] ?? '',
                    'value' => $value,
                    'tokenDecimal' => 6,
                    'contractAddress' => $tx['token_info']['address'] ?? '',
                    'timeStamp' => isset($tx['block_timestamp']) ? ($tx['block_timestamp'] / 1000) : time(),
                ];
            })->toArray();

            return $transfers;

        } catch (\Exception $e) {
            Log::error("NodeReal Tron API Error: " . $e->getMessage());
            return [];
        }
    }

    public function getFilteredTransactions($transactions, $contractAddress, $toAddress, $fromAddresses, $mergedHashes) {
        return collect($transactions)->filter(function ($tx) use ($toAddress, $fromAddresses, $contractAddress, $mergedHashes) {
            $from       = strtolower($tx['from'] ?? '');
            $to         = strtolower($tx['to'] ?? '');
            $hash       = strtolower($tx['hash'] ?? '');
            $contract   = strtolower($tx['contractAddress'] ?? '');

            return (
                strtolower($contractAddress) === $contract &&
                $to == strtolower($toAddress) &&
                in_array($from, $fromAddresses) &&
                !in_array($hash, $mergedHashes)
            );
        })->values();
    }
}
