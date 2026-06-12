<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TransactionsService;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TransactionsController extends Controller
{
    public function withdrawlCreate(Request $request){
        $transaction_fees_percentage = 0.06;
        $user = authUser();

        // Restriction: Wallet Address Must Be Set First
        $userWallets = $user->wallet_addresses ?? [];
        if (empty($userWallets['BEP-20'])) {
            return redirect()->route('edit.wallet.address')->with('error', 'Error|Please add your BEP-20 wallet address first before making a withdrawal.');
        }

        $totalBalance = $user->income_balance;
        $lockedTrading = $user->getTradingLockedAmount();
        $availableBalance = round($totalBalance - $lockedTrading - 50, 2);
        if ($availableBalance < 0) {
            $availableBalance = 0;
        }

        if($request->isMethod('post')){
             if(!env('WITHDRAWAL_ACTIVE')){
                return response()->json([
                    'status' => false, 
                    'message' => 'Withdrawal is temporarily Inactive. Please contact your upline for assistance.', 
                    'code' => 400
                ]);
            }
            return (new TransactionsService)->addTransaction($request);
        }

        $activeCategory = $user->agentCategory();
        $maxSingleLimit = 0;
        if ($activeCategory) {
            $maxSingleLimit = ($activeCategory->unlock_balance == 50) ? 200 : $activeCategory->unlock_balance;
        }

        return view('wallet.withdrawl',compact('transaction_fees_percentage', 'availableBalance', 'lockedTrading', 'maxSingleLimit', 'activeCategory'));
    }
    // public function setWalletAddress(Request $request){
    //     $editable = true;
    //     if($request->isMethod('post')){
    //         return (new TransactionsService)->addWalletAddress($request);
    //     }
    //     return view('wallet.withdrawl',compact('editable'));
    // }
    public function walletDeposit(Request $request){
        if($request->isMethod('post')){
            return (new TransactionsService)->addDepositTransaction($request);
        }
        $user = authUser();

        // Restriction: Wallet Address Must Be Set First
        $userWallets = $user->wallet_addresses ?? [];
        // if (empty($userWallets['BEP-20'])) {
        //     return redirect()->route('edit.wallet.address')->with('error', 'Error|Please add your BEP-20 wallet address first.');
        // }

        $type = request()->get('type') ?? 'BEP-20';
        $address = getWalletAddress(adminMember(), $type);
        $qrCode = null;
        if($address != null){
            $qrCode = QrCode::size(200)->generate($address);
        }
        return view('wallet.deposit',compact('address','qrCode','type'));
    }

    public function setTransactionPin(Request $request){
        if($request->isMethod('post')){
            return (new TransactionsService)->setTransactionPin($request);
        }
        return view('wallet.set-pin');
    }
    public function editWalletAddress(Request $request){
        if($request->isMethod('post')){
            return (new TransactionsService)->editWalletAddress($request);
        }
        return view('wallet.edit-wallet-address');
    }
}
