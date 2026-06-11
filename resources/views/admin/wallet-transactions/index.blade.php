@extends('admin.layouts.admin')
@section('title','USDT Transfer')
@section('content')
    <div class="row">
        <div class="col-md-12 mb-3">
            <h4 class="card-title">USDT Transfer (BEP-20 & TRC-20)</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">BEP-20 (MetaMask)</h4>
                    <div class="template-demo">
                        <button class="btn btn-main btn-fw" onclick="connectMetaMask()">Connect MetaMask</button>
                        <button class="btn btn-info btn-fw" onclick="sendBEP20()">Send USDT (BEP20)</button>
                        <button class="btn btn-success btn-fw" onclick="getBEP20Balance()">Check USDT Balance (BEP20)</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">TRC-20 (TronLink)</h4>
                    <div class="template-demo">
                        <button class="btn btn-main btn-fw" onclick="connectTron()">Connect TronLink</button>
                        <button class="btn btn-info btn-fw" onclick="sendTRC20()">Send USDT (TRC20)</button>
                        <button class="btn btn-success btn-fw" onclick="getTRC20Balance()">Check USDT Balance (TRC20)</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/ethers@5.7.2/dist/ethers.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tronweb/dist/TronWeb.js"></script>
    <script>
        // BEP-20 Logic (Binance Smart Chain)
        const BEP20_USDT = "0x55d398326f99059fF775485246999027B3197955"; // Mainnet
        const BEP20_ABI = [
          "function transfer(address to, uint256 amount) public returns (bool)",
          "function balanceOf(address owner) view returns (uint)"
        ];
        let bep20Wallet = "";

        async function connectMetaMask() {
          if (window.ethereum) {
            const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
            bep20Wallet = accounts[0];
            alert("Connected MetaMask: " + bep20Wallet);
          } else {
            alert("MetaMask not installed");
          }
        }

        async function sendBEP20() {
          const recipient = prompt("Enter recipient address:");
          const amount = prompt("Enter amount (e.g., 10):");

          const provider = new ethers.providers.Web3Provider(window.ethereum);
          const signer = provider.getSigner();
          const usdt = new ethers.Contract(BEP20_USDT, BEP20_ABI, signer);

          const tx = await usdt.transfer(recipient, ethers.utils.parseUnits(amount, 18));
          alert("Transaction sent: " + tx.hash);
        }

        async function getBEP20Balance() {
          const provider = new ethers.providers.Web3Provider(window.ethereum);
          const usdt = new ethers.Contract(BEP20_USDT, BEP20_ABI, provider);
          const balance = await usdt.balanceOf(bep20Wallet);
          alert("USDT Balance (BEP-20): " + ethers.utils.formatUnits(balance, 18));
        }

        // TRC-20 Logic (Tron Network)
        const TRC20_USDT = "TXLAQ63Xg1NAzckPwKHvzw7CSEmLMEqcdj"; // Mainnet
        let tronWallet = "";

        async function connectTron() {
          if (window.tronWeb && window.tronWeb.ready) {
            tronWallet = window.tronWeb.defaultAddress.base58;
            alert("Connected TronLink: " + tronWallet);
          } else {
            alert("TronLink not installed or not logged in");
          }
        }

        async function sendTRC20() {
          const recipient = prompt("Enter recipient address:");
          const amount = prompt("Enter amount (e.g., 10):");

          const contract = await window.tronWeb.contract().at(TRC20_USDT);
          const tx = await contract.transfer(recipient, amount * 1_000_000).send();
          alert("Transaction ID: " + tx);
        }

        async function getTRC20Balance() {
          const contract = await window.tronWeb.contract().at(TRC20_USDT);
          const balance = await contract.balanceOf(tronWallet).call();
          alert("USDT Balance (TRC-20): " + (balance / 1_000_000));
        }
    </script>
@endsection
