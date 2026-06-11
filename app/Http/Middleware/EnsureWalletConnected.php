<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureWalletConnected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->guard('member')->user();
        if(!$user || (empty($user->bep20_wallet_address) && empty($user->wallet_addresses))){
            session()->flash('error', 'Please connect your BEP-20 or TRC-20 wallet address before accessing the deposit section.');
            return redirect()->route('edit.wallet.address');
        }
        return $next($request);
    }
}
