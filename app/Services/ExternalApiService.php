<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Exception;
use Illuminate\Support\Facades\Log;

class ExternalApiService
{
    protected $baseUrl;
    protected $apiCredentials;

    public function __construct()
    {
        $this->baseUrl = 'http://192.168.11.39:777';
        $this->apiCredentials = [
            'username' => env('EXTERNAL_API_USERNAME'),
            'password' => env('EXTERNAL_API_PASSWORD')
        ];
    }

    /**
     * Get JWT Token from external API
     */
    private function getExternalToken()
    {
        // Your existing token method
        if (Cache::has('external_api_token')) {
            return Cache::get('external_api_token');
        }

        $response = Http::timeout(30)->post($this->baseUrl . '/api/Auth/login', [
            'Username' => $this->apiCredentials['username'],
            'Password' => $this->apiCredentials['password']
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $token = $data['access_token'] ?? $data['token'] ?? $data['jwt_token'] ?? $data['accessToken'] ?? null;
            
            if ($token) {
                Cache::put('external_api_token', $token, now()->addMinutes(55));
                return $token;
            }
        }

        throw new Exception('Failed to get token. Status: ' . $response->status() . ' - Body: ' . $response->body());
    }

    /**
     * Generic method to fetch data from any endpoint
     */
    public function fetchData($endpoint)
    {
        $token = $this->getExternalToken();

        $response = Http::timeout(30)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->get($this->baseUrl . $endpoint);

        if ($response->successful()) {
            $data = $response->json();
            
            // Handle different response formats
            if (isset($data['data'])) {
                return $data['data'];
            } elseif (isset($data['items'])) {
                return $data['items'];
            } else {
                return $data;
            }
        }

        throw new Exception('API request failed for ' . $endpoint . '. Status: ' . $response->status() . ' - ' . $response->body());
    }

    // Specific methods
    public function getUsers()
    {
        return $this->fetchData('/api/user');
    }

    public function getWorkOrders()
    {
        return $this->fetchData('/api/WorkOrder');
    }

    public function getReceiveWashTransactions()
    {
        return $this->fetchData('/api/WashTransaction/receive');

    }

    public function getDeliveryWashTransactions()
    {
        return $this->fetchData('/api/WashTransaction/delivery');
    }

    public function getDeliveryWashTransactionsAll()
    {
        return $this->fetchData('/api/WashTransaction');

    }

    public function getAllData()
    {
        return [
            'users' => $this->getUsers(),
            'work_orders' => $this->getWorkOrders(),
            'receive_transactions' => $this->getReceiveWashTransactions(),
            'delivery_transactions' => $this->getDeliveryWashTransactions(),
            'transactions' => $this->getDeliveryWashTransactionsAll()
        ];
    }
}