<?php
// app/Services/EppayService.php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EppayService
{
    private $apiKey;
    private $apiUrl;
    private $walletAddress;
    private $rpcUrl;
    private $tokenAddress;

    public function __construct()
    {
        $this->apiKey = config('services.eppay.api_key');
        $this->apiUrl = config('services.eppay.api_url');
        $this->walletAddress = config('services.eppay.wallet_address');
        $this->rpcUrl = config('services.eppay.rpc_url');
        $this->tokenAddress = config('services.eppay.token_address');
    }

    public function generatePayment($amount, $successUrl, $walletAddress = null)
    {
        try {

            // Use provided wallet address or fall back to default from config
            $toAddress = $walletAddress ?: $this->walletAddress;
            
            // Validate wallet address format
            if (!$this->isValidWalletAddress($toAddress)) {
                Log::error('Invalid wallet address', ['address' => $toAddress]);
                // Fall back to platform wallet if seller wallet is invalid
                $toAddress = $this->walletAddress;
            }
            

            $response = Http::post($this->apiUrl . '/generate-code', [
                'apiKey' => $this->apiKey,
                'amount' => (string) $amount,
                'to' => $toAddress,
                'rpc' => $this->rpcUrl,
                'token' => $this->tokenAddress,
                'success' => $successUrl,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Eppay API error', ['response' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('Eppay API exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Validate wallet address format
     */
    private function isValidWalletAddress($address)
    {
        // Basic validation for Ethereum-style addresses
        return preg_match('/^0x[a-fA-F0-9]{40}$/', $address);
    }

    public function checkPaymentStatus($paymentId)
    {
        try {
            $response = Http::get($this->apiUrl . '/payment-status/' . $paymentId);

            if ($response->successful()) {
                return $response->json()['status'] ?? false;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Eppay status check error', ['error' => $e->getMessage()]);
            return false;
        }
    }
}