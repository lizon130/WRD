<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MachineControllerApi extends Controller
{
    public function getWashProductionData(Request $request)
    {
        try {
            // Get filter parameters
            $dateFrom = $request->date_from ?: date('Y-m-d');
            $dateTo = $request->date_to ?: date('Y-m-d');
            $unitId = $request->unit_id;
            
            // Get all units
            $unitsQuery = Unit::query();
            if ($unitId) {
                $unitsQuery->where('id', $unitId);
            }
            $units = $unitsQuery->get();
            
            // Get all dates in the range
            $dates = $this->getDatesInRange($dateFrom, $dateTo);
            
            $washProductionData = [];
            $summary = [
                'total_received' => 0,
                'total_delivery' => 0,
                'unit_count' => 0,
                'date_range' => [
                    'from' => $dateFrom,
                    'to' => $dateTo
                ]
            ];
            
            foreach ($dates as $date) {
                $dateData = [
                    'date' => $date,
                    'display_date' => date('d M, Y', strtotime($date)),
                    'units' => [],
                    'daily_totals' => [
                        'total_received' => 0,
                        'total_delivery' => 0,
                        'unit_count' => 0
                    ]
                ];
                
                foreach ($units as $unit) {
                    $washData = $this->getWashProductionDataForUnitAndDate($date, $unit);
                    
                    $unitData = [
                        'unit_id' => $unit->id,
                        'unit_name' => $unit->unitName,
                        'received' => $washData['received'],
                        'delivery' => $washData['delivery'],
                        'net_production' => $washData['received'] - $washData['delivery'],
                        'date' => $date
                    ];
                    
                    $dateData['units'][] = $unitData;
                    $dateData['daily_totals']['total_received'] += $washData['received'];
                    $dateData['daily_totals']['total_delivery'] += $washData['delivery'];
                    $dateData['daily_totals']['unit_count']++;
                    
                    // Add to overall summary
                    $summary['total_received'] += $washData['received'];
                    $summary['total_delivery'] += $washData['delivery'];
                }
                
                $summary['unit_count'] = $units->count();
                $washProductionData[] = $dateData;
            }
            
            return response()->json([
                'success' => true,
                'summary' => $summary,
                'data' => $washProductionData,
                'message' => 'Wash production data retrieved successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Wash production API error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve wash production data',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getWashProductionByDate(Request $request, $date)
    {
        try {
            // Validate date format
            if (!strtotime($date)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid date format. Use YYYY-MM-DD'
                ], 400);
            }
            
            $unitId = $request->unit_id;
            $unitsQuery = Unit::query();
            
            if ($unitId) {
                $unitsQuery->where('id', $unitId);
            }
            
            $units = $unitsQuery->get();
            $unitsData = [];
            $totals = [
                'total_received' => 0,
                'total_delivery' => 0
            ];
            
            foreach ($units as $unit) {
                $washData = $this->getWashProductionDataForUnitAndDate($date, $unit);
                
                $unitData = [
                    'unit_id' => $unit->id,
                    'unit_name' => $unit->unitName,
                    'received' => $washData['received'],
                    'delivery' => $washData['delivery'],
                    'net_production' => $washData['received'] - $washData['delivery']
                ];
                
                $unitsData[] = $unitData;
                $totals['total_received'] += $washData['received'];
                $totals['total_delivery'] += $washData['delivery'];
            }
            
            return response()->json([
                'success' => true,
                'date' => $date,
                'display_date' => date('d M, Y', strtotime($date)),
                'totals' => $totals,
                'units' => $unitsData,
                'unit_count' => count($unitsData)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Wash production by date API error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve wash production data for date: ' . $date,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getWashProductionByUnit(Request $request, $unitId)
    {
        try {
            $dateFrom = $request->date_from ?: date('Y-m-d', strtotime('-7 days'));
            $dateTo = $request->date_to ?: date('Y-m-d');
            
            $unit = Unit::find($unitId);
            
            if (!$unit) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unit not found'
                ], 404);
            }
            
            $dates = $this->getDatesInRange($dateFrom, $dateTo);
            $unitData = [];
            $totals = [
                'total_received' => 0,
                'total_delivery' => 0,
                'average_daily_received' => 0,
                'average_daily_delivery' => 0
            ];
            
            foreach ($dates as $date) {
                $washData = $this->getWashProductionDataForUnitAndDate($date, $unit);
                
                $dayData = [
                    'date' => $date,
                    'display_date' => date('d M, Y', strtotime($date)),
                    'received' => $washData['received'],
                    'delivery' => $washData['delivery'],
                    'net_production' => $washData['received'] - $washData['delivery']
                ];
                
                $unitData[] = $dayData;
                $totals['total_received'] += $washData['received'];
                $totals['total_delivery'] += $washData['delivery'];
            }
            
            $dayCount = count($dates);
            $totals['average_daily_received'] = $dayCount > 0 ? round($totals['total_received'] / $dayCount, 2) : 0;
            $totals['average_daily_delivery'] = $dayCount > 0 ? round($totals['total_delivery'] / $dayCount, 2) : 0;
            
            return response()->json([
                'success' => true,
                'unit' => [
                    'id' => $unit->id,
                    'name' => $unit->unitName,
                    'machine_count' => $unit->machineCount,
                    'mg_target' => $unit->mgTarget
                ],
                'date_range' => [
                    'from' => $dateFrom,
                    'to' => $dateTo,
                    'days' => $dayCount
                ],
                'totals' => $totals,
                'daily_data' => $unitData
            ]);
            
        } catch (\Exception $e) {
            Log::error('Wash production by unit API error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve wash production data for unit ID: ' . $unitId,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getWashProductionSummary(Request $request)
    {
        try {
            $dateFrom = $request->date_from ?: date('Y-m-d', strtotime('-30 days'));
            $dateTo = $request->date_to ?: date('Y-m-d');
            
            $units = Unit::all();
            $dates = $this->getDatesInRange($dateFrom, $dateTo);
            
            $summaryData = [];
            $unitSummaries = [];
            
            // Initialize unit summaries
            foreach ($units as $unit) {
                $unitSummaries[$unit->id] = [
                    'unit_id' => $unit->id,
                    'unit_name' => $unit->unitName,
                    'total_received' => 0,
                    'total_delivery' => 0,
                    'daily_average_received' => 0,
                    'daily_average_delivery' => 0
                ];
            }
            
            // Collect data
            foreach ($dates as $date) {
                $dayTotalReceived = 0;
                $dayTotalDelivery = 0;
                
                foreach ($units as $unit) {
                    $washData = $this->getWashProductionDataForUnitAndDate($date, $unit);
                    
                    $unitSummaries[$unit->id]['total_received'] += $washData['received'];
                    $unitSummaries[$unit->id]['total_delivery'] += $washData['delivery'];
                    
                    $dayTotalReceived += $washData['received'];
                    $dayTotalDelivery += $washData['delivery'];
                }
                
                $summaryData[] = [
                    'date' => $date,
                    'display_date' => date('d M, Y', strtotime($date)),
                    'daily_total_received' => $dayTotalReceived,
                    'daily_total_delivery' => $dayTotalDelivery,
                    'daily_net_production' => $dayTotalReceived - $dayTotalDelivery
                ];
            }
            
            // Calculate averages
            $dayCount = count($dates);
            $totalOverallReceived = 0;
            $totalOverallDelivery = 0;
            
            foreach ($unitSummaries as &$unitSummary) {
                $unitSummary['daily_average_received'] = $dayCount > 0 ? 
                    round($unitSummary['total_received'] / $dayCount, 2) : 0;
                $unitSummary['daily_average_delivery'] = $dayCount > 0 ? 
                    round($unitSummary['total_delivery'] / $dayCount, 2) : 0;
                
                $totalOverallReceived += $unitSummary['total_received'];
                $totalOverallDelivery += $unitSummary['total_delivery'];
            }
            
            $overallSummary = [
                'total_received' => $totalOverallReceived,
                'total_delivery' => $totalOverallDelivery,
                'net_production' => $totalOverallReceived - $totalOverallDelivery,
                'average_daily_received' => $dayCount > 0 ? round($totalOverallReceived / $dayCount, 2) : 0,
                'average_daily_delivery' => $dayCount > 0 ? round($totalOverallDelivery / $dayCount, 2) : 0,
                'date_range' => [
                    'from' => $dateFrom,
                    'to' => $dateTo,
                    'days' => $dayCount
                ],
                'unit_count' => count($units)
            ];
            
            return response()->json([
                'success' => true,
                'overall_summary' => $overallSummary,
                'unit_summaries' => array_values($unitSummaries),
                'daily_summary' => $summaryData
            ]);
            
        } catch (\Exception $e) {
            Log::error('Wash production summary API error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve wash production summary',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    // ============ PRIVATE HELPER METHODS ============
    
    /**
     * Get all dates between start and end date
     */
    private function getDatesInRange($startDate, $endDate)
    {
        $dates = [];
        
        // Handle invalid dates
        if (empty($startDate) || empty($endDate)) {
            $startDate = date('Y-m-d');
            $endDate = date('Y-m-d');
        }
        
        $current = strtotime($startDate);
        $end = strtotime($endDate);
        
        // If dates are invalid, return current date
        if (!$current || !$end) {
            return [date('Y-m-d')];
        }
        
        // Ensure start date is before end date
        if ($current > $end) {
            // Swap dates if start is after end
            $temp = $current;
            $current = $end;
            $end = $temp;
        }
        
        while ($current <= $end) {
            $dates[] = date('Y-m-d', $current);
            $current = strtotime('+1 day', $current);
        }
        
        return $dates;
    }
    
    /**
     * Get wash production data for a specific unit and date
     * TODO: Replace with your actual database query
     */
    private function getWashProductionDataForUnitAndDate($date, $unit)
    {
        // TEMPORARY: Return dummy data for testing
        // Remove this and implement actual database query
        
        return [
            'received' => rand(100, 1000),
            'delivery' => rand(50, 800),
        ];
    }
}