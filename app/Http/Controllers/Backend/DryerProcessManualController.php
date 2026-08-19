<?php
// app/Http/Controllers/Backend/DryerProcessManualController.php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DryerProcessManual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DryerProcessManualController extends Controller
{
    /**
     * Display the index page
     */
    public function index()
    {
        // Get distinct plants from SQL Server
        $plants = DB::connection('sqlsrv_third')
            ->table('WorkOrders')
            ->select('Unit')
            ->distinct()
            ->whereNotNull('Unit')
            ->orderBy('Unit')
            ->pluck('Unit');
            
        return view('backend.dryer-process-manual.index', compact('plants'));
    }

    /**
     * Get data for DataTable - THIS IS WHERE THE QUERY IS
     */
    public function getList(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $specificDate = $request->get('date');
        $plantName = $request->get('plantName');

        // YOUR EXACT QUERY IS HERE
        $query = DB::connection('sqlsrv_third')
            ->table('WorkOrders as wo')
            ->crossJoin('ProcessStages as ps')
            ->leftJoin('WashTransactions as wt', function($join) {
                $join->on('wt.WorkOrderId', '=', 'wo.Id')
                     ->on('wt.ProcessStageId', '=', 'ps.Id')
                     ->where('wt.TransactionType', '=', 'Delivery');
            })
            ->where('ps.Id', '>=', 6)
            ->select(
                'wo.Unit',
                'ps.Name as ProcessStageName',
                DB::raw('CAST(wt.TransactionDate AS DATE) as TransactionDate'),
                DB::raw('ISNULL(SUM(wt.Quantity), 0) as TotalQuantity')
            )
            ->groupBy('wo.Unit', 'ps.Name', DB::raw('CAST(wt.TransactionDate AS DATE)'))
            ->orderBy('wo.Unit')
            ->orderBy('ps.Name');

        // Apply filters
        if ($specificDate) {
            $query->having(DB::raw('CAST(wt.TransactionDate AS DATE)'), '=', $specificDate);
        } else {
            if ($startDate) {
                $query->having(DB::raw('CAST(wt.TransactionDate AS DATE)'), '>=', $startDate);
            }
            if ($endDate) {
                $query->having(DB::raw('CAST(wt.TransactionDate AS DATE)'), '<=', $endDate);
            }
        }

        if ($plantName) {
            $query->where('wo.Unit', $plantName);
        }

        // Execute the query
        $washData = $query->get();

        // Get manual adjusted data from local database
        $manualData = DryerProcessManual::when($specificDate, function($query) use ($specificDate) {
                return $query->whereDate('TransactionDate', $specificDate);
            })
            ->when($startDate, function($query) use ($startDate) {
                return $query->whereDate('TransactionDate', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->whereDate('TransactionDate', '<=', $endDate);
            })
            ->when($plantName, function($query) use ($plantName) {
                return $query->where('unit', $plantName);
            })
            ->get()
            ->groupBy(function($item) {
                return $item->TransactionDate . '|' . $item->unit . '|' . $item->processStageName;
            });

        // Merge manual data with wash data (override if manual exists)
        foreach ($washData as $data) {
            $key = $data->TransactionDate . '|' . $data->Unit . '|' . $data->ProcessStageName;
            if (isset($manualData[$key])) {
                $manualRecord = $manualData[$key]->first();
                $data->TotalQuantity = $manualRecord->totalQty; // Use manual value instead
                $data->is_manual = true;
                $data->manual_id = $manualRecord->id;
            } else {
                $data->is_manual = false;
                $data->manual_id = null;
            }
        }

        return response()->json([
            'data' => $washData
        ]);
    }

    /**
     * Show create/edit form
     */
    public function createForm(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $unit = $request->get('unit');
        
        // Get distinct plants
        $plants = DB::connection('sqlsrv_third')
            ->table('WorkOrders')
            ->select('Unit')
            ->distinct()
            ->whereNotNull('Unit')
            ->orderBy('Unit')
            ->pluck('Unit');
        
        // Get process stages (Id >= 6)
        $processStages = DB::connection('sqlsrv_third')
            ->table('ProcessStages')
            ->where('Id', '>=', 6)
            ->orderBy('Id')
            ->get();
        
        // Get existing manual data for this date and unit
        $existingData = [];
        if ($unit) {
            $existingData = DryerProcessManual::where('TransactionDate', $date)
                ->where('unit', $unit)
                ->get()
                ->keyBy('processStageName');
        }
        
        // Get the SQL Server data for this date and unit to display
        $sqlServerData = [];
        if ($unit) {
            $sqlServerData = DB::connection('sqlsrv_third')
                ->table('WorkOrders as wo')
                ->crossJoin('ProcessStages as ps')
                ->leftJoin('WashTransactions as wt', function($join) {
                    $join->on('wt.WorkOrderId', '=', 'wo.Id')
                         ->on('wt.ProcessStageId', '=', 'ps.Id')
                         ->where('wt.TransactionType', '=', 'Delivery');
                })
                ->where('ps.Id', '>=', 6)
                ->where('wo.Unit', $unit)
                ->whereDate('wt.TransactionDate', $date)
                ->select(
                    'ps.Name as ProcessStageName',
                    DB::raw('ISNULL(SUM(wt.Quantity), 0) as TotalQuantity')
                )
                ->groupBy('ps.Name')
                ->get()
                ->keyBy('ProcessStageName');
        }
        
        return view('backend.dryer-process-manual.create', compact('date', 'unit', 'plants', 'processStages', 'existingData', 'sqlServerData'));
    }

    /**
 * Store or update data
 */
public function store(Request $request)
{
    \Log::info('Store method called', $request->all());
    
    $validator = Validator::make($request->all(), [
        'date' => 'required|date',
        'unit' => 'required|string',
        'items' => 'required|array',
        'items.*.process_stage_name' => 'required|string',
        'items.*.total_qty' => 'nullable|integer|min:0',
    ]);

    if ($validator->fails()) {
        \Log::error('Validation failed', $validator->errors()->toArray());
        return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
    }

    try {
        // Delete existing records for this date and unit
        $deleted = DryerProcessManual::where('TransactionDate', $request->date)
            ->where('unit', $request->unit)
            ->delete();
        
        \Log::info('Deleted records: ' . $deleted);

        // Insert new records (only save if quantity is provided)
        $inserted = 0;
        foreach ($request->items as $item) {
            if ($item['total_qty'] !== null && $item['total_qty'] !== '') {
                $created = DryerProcessManual::create([
                    'TransactionDate' => $request->date,
                    'unit' => $request->unit,
                    'processStageName' => $item['process_stage_name'],
                    'totalQty' => $item['total_qty'],
                ]);
                if ($created) {
                    $inserted++;
                }
            }
        }
        
        \Log::info('Inserted records: ' . $inserted);

        return response()->json([
            'success' => true,
            'message' => 'Data saved successfully! ' . $inserted . ' records saved.'
        ]);
    } catch (\Exception $e) {
        \Log::error('Save error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error saving data: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Delete data
     */
    public function delete($id)
    {
        try {
            $data = DryerProcessManual::findOrFail($id);
            $data->delete();
                
            return response()->json([
                'success' => true,
                'message' => 'Data deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting data: ' . $e->getMessage()
            ], 500);
        }
    }
}