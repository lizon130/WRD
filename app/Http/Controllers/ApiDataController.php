<?php

namespace App\Http\Controllers;

use App\Services\ExternalApiService;
use Exception;

class ApiDataController extends Controller
{
    protected $externalApiService;

    public function __construct()
    {
        $this->externalApiService = new ExternalApiService();
    }

    /**
     * Get Users from External API
     */
    public function getUsers()
    {
        try {
            $users = $this->externalApiService->getUsers();

            return response()->json([
                'success' => true,
                'data' => $users,
                'count' => is_array($users) ? count($users) : null,
                'timestamp' => now()->toDateTimeString()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ], 500);
        }
    }

    /**
     * Get Work Orders from External API
     */
    public function getWorkOrders()
    {
        try {
            $workOrders = $this->externalApiService->getWorkOrders();

            return response()->json([
                'success' => true,
                'data' => $workOrders,
                'count' => is_array($workOrders) ? count($workOrders) : null,
                'timestamp' => now()->toDateTimeString()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ], 500);
        }
    }

    /**
     * Get Receive Wash Transactions from External API
     */
    public function getReceiveTransactions()
    {
        try {
            $transactions = $this->externalApiService->getReceiveWashTransactions();

            return response()->json([
                'success' => true,
                'data' => $transactions,
                'count' => is_array($transactions) ? count($transactions) : null,
                'timestamp' => now()->toDateTimeString()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ], 500);
        }
    }

    /**
     * Get Delivery Wash Transactions from External API
     */
    public function getDeliveryTransactions()
    {
        try {
            $transactions = $this->externalApiService->getDeliveryWashTransactions();

            return response()->json([
                'success' => true,
                'data' => $transactions,
                'count' => is_array($transactions) ? count($transactions) : null,
                'timestamp' => now()->toDateTimeString()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ], 500);
        }
    }

    public function getDeliveryWashTransactionsAll()
    {
        try {
            $transactions = $this->externalApiService->getDeliveryWashTransactionsAll();

            return response()->json([
                'success' => true,
                'data' => $transactions,
                'count' => is_array($transactions) ? count($transactions) : null,
                'timestamp' => now()->toDateTimeString()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ], 500);
        }
    }

    /**
     * Get All Data from External API
     */
    public function getAllData()
    {
        try {
            $allData = $this->externalApiService->getAllData();

            return response()->json([
                'success' => true,
                'data' => $allData,
                'counts' => [
                    'users' => is_array($allData['users']) ? count($allData['users']) : null,
                    'work_orders' => is_array($allData['work_orders']) ? count($allData['work_orders']) : null,
                    'receive_transactions' => is_array($allData['receive_transactions']) ? count($allData['receive_transactions']) : null,
                    'delivery_transactions' => is_array($allData['delivery_transactions']) ? count($allData['delivery_transactions']) : null,
                ],
                'timestamp' => now()->toDateTimeString()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ], 500);
        }
    }
}