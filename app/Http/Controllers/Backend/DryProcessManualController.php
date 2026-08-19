<?php
// app/Http/Controllers/Backend/DryProcessManualController.php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DryProcessManual;
use App\Models\SecondDryProcessEntry;
use App\Models\DryProcessIE;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class DryProcessManualController extends Controller
{
    /**
     * Display the index page
     */
    public function index()
    {
        $plants = ['TPL', 'TWL'];
        return view('backend.dry-process-manual.index', compact('plants'));
    }

    /**
     * Get data for DataTable (from local database)
     */
    public function getList(Request $request)
    {
        $query = DryProcessManual::query();

        // Apply filters
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('plantName')) {
            $query->where('plantName', $request->plantName);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return '
                    <button type="button" class="btn btn-sm btn-primary edit_btn" data-id="' . $row->id . '">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger delete_btn" data-id="' . $row->id . '">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                ';
            })
            ->editColumn('date', function ($row) {
                return date('d-m-Y', strtotime($row->date));
            })
            ->addColumn('whisker_achievement', function ($row) {
                return $this->calculateAchievement($row->whisker_target, $row->whisker_production);
            })
            ->addColumn('handBrush_achievement', function ($row) {
                return $this->calculateAchievement($row->handBrush_target, $row->handBrush_production);
            })
            ->addColumn('FirstDryFinal_achievement', function ($row) {
                return $this->calculateAchievement($row->FirstDryFinal_target, $row->FirstDryFinal_production);
            })
            ->addColumn('SecondDryFinal_achievement', function ($row) {
                return $this->calculateAchievement($row->SecondDryFinal_target, $row->SecondDryFinal_production);
            })
            ->rawColumns(['action', 'whisker_achievement', 'handBrush_achievement', 'FirstDryFinal_achievement', 'SecondDryFinal_achievement'])
            ->make(true);
    }

    /**
     * Calculate achievement percentage with badge
     */
    private function calculateAchievement($target, $production)
    {
        if ($target > 0) {
            $percentage = ($production / $target) * 100;
            $badgeClass = $percentage >= 100 ? 'success' : ($percentage >= 80 ? 'warning' : 'danger');
            return '<span class="badge bg-' . $badgeClass . '">' . number_format($percentage, 2) . '%</span>';
        }
        return '<span class="badge bg-secondary">0%</span>';
    }

    /**
     * Get data from First Dry Process (ALWAYS from original source - QC database)
     * This is for the Create Form - users need to see source data to modify
     */
    private function getFirstDryProcessDataFromSource($date)
    {
        try {
            $result = [
                'TPL' => [
                    'whisker_target' => 0,
                    'whisker_production' => 0,
                    'handbrush_target' => 0,
                    'handbrush_production' => 0,
                    'firstdryfinal_target' => 0,
                    'firstdryfinal_production' => 0,
                    'firstdryfinal_defect' => 0
                ],
                'TWL' => [
                    'whisker_target' => 0,
                    'whisker_production' => 0,
                    'handbrush_target' => 0,
                    'handbrush_production' => 0,
                    'firstdryfinal_target' => 0,
                    'firstdryfinal_production' => 0,
                    'firstdryfinal_defect' => 0
                ]
            ];

            // ========== PART 1: GET TPL DATA FROM QC DATABASE ==========
            $query = "
            SELECT
                fc.PlantName,
                wp.ProcessName,
                SUM(fc.ProductionQty) AS ProductionQty,
                SUM(fc.DefectQty) AS DefectQty,
                SUM(ISNULL(t.DayTarget, 0)) AS TargetQty
            FROM
            (
                SELECT 
                    p.PlantName,
                    pu.UnitName,
                    fq.QcDate,
                    fq.WashProcessId,
                    SUM(CASE WHEN fq.QcStatusId IN (1,3) THEN 1 ELSE 0 END) AS ProductionQty,
                    SUM(CASE WHEN fq.QcStatusId = 2 THEN 1 ELSE 0 END) AS DefectQty
                FROM [DHU_WashDB].[dbo].[FirstDryProcessQc] fq
                JOIN FirstDryProcess fp ON fq.FirstDryProcessId = fp.Id
                JOIN PlantUnit pu ON fp.UnitId = pu.id
                JOIN Plant p ON pu.PlantId = p.Id
                WHERE fq.IsDeleted = 0
                AND fq.QcDate = ?
                GROUP BY 
                    p.PlantName,
                    pu.UnitName,
                    fq.QcDate,
                    fq.WashProcessId
            ) fc
            LEFT JOIN
            (
                SELECT 
                    wh.WorkingHourDay AS [Date],
                    whdpm.WashProcessId,
                    SUM(whdpm.DailyTarget) AS DayTarget
                FROM WorkingHourDetailManPower whdpm
                JOIN WorkingHourDetail whd ON whdpm.WorkingHourDetailId = whd.Id
                JOIN WorkingHour wh ON whd.WorkingHourId = wh.Id
                WHERE wh.WorkingHourDay = ?
                GROUP BY 
                    wh.WorkingHourDay,
                    whdpm.WashProcessId
            ) t
                ON t.WashProcessId = fc.WashProcessId
                AND t.[Date] = fc.QcDate
            JOIN WashProcess wp 
                ON wp.Id = fc.WashProcessId
            WHERE wp.ProcessName IN ('Whisker', 'Handbrush', 'First Dry Final')
            GROUP BY fc.PlantName, wp.ProcessName
            ORDER BY fc.PlantName, wp.ProcessName;
        ";

            $params = [$date, $date];
            $results = DB::connection('sqlsrv_second')->select($query, $params);

            // Parse TPL results
            foreach ($results as $row) {
                $plant = $row->PlantName;
                if ($plant == 'TPL') {
                    switch ($row->ProcessName) {
                        case 'Whisker':
                            $result['TPL']['whisker_target'] = (int)$row->TargetQty;
                            $result['TPL']['whisker_production'] = (int)$row->ProductionQty;
                            break;
                        case 'Handbrush':
                            $result['TPL']['handbrush_target'] = (int)$row->TargetQty;
                            $result['TPL']['handbrush_production'] = (int)$row->ProductionQty;
                            break;
                        case 'First Dry Final':
                            $result['TPL']['firstdryfinal_target'] = (int)$row->TargetQty;
                            $result['TPL']['firstdryfinal_production'] = (int)$row->ProductionQty;
                            $result['TPL']['firstdryfinal_defect'] = (int)($row->DefectQty ?? 0);
                            break;
                    }
                }
            }

            // ========== PART 2: GET TWL DATA FROM SecondDryProcessEntry ==========
            // Whisker
            $whiskerData = SecondDryProcessEntry::where('plant', 'TWL')
                ->where('processType', 'Whisker')
                ->where('date', $date)
                ->first();

            if ($whiskerData) {
                $result['TWL']['whisker_target'] = (int)($whiskerData->TargetQty ?? 0);
                $result['TWL']['whisker_production'] = (int)($whiskerData->ProductionQty ?? 0);
            }

            // Hand Brush
            $handBrushData = SecondDryProcessEntry::where('plant', 'TWL')
                ->where('processType', 'Hand Brush')
                ->where('date', $date)
                ->first();

            if ($handBrushData) {
                $result['TWL']['handbrush_target'] = (int)($handBrushData->TargetQty ?? 0);
                $result['TWL']['handbrush_production'] = (int)($handBrushData->ProductionQty ?? 0);
            }

            // 1st Dry Final
            $firstDryFinalData = SecondDryProcessEntry::where('plant', 'TWL')
                ->where('processType', '1st Dry Final')
                ->where('date', $date)
                ->first();

            if ($firstDryFinalData) {
                $result['TWL']['firstdryfinal_target'] = (int)($firstDryFinalData->TargetQty ?? 0);
                $result['TWL']['firstdryfinal_production'] = (int)($firstDryFinalData->ProductionQty ?? 0);
                $result['TWL']['firstdryfinal_defect'] = (int)($firstDryFinalData->defectQty ?? 0);
            }

            \Log::info('First Dry Data fetched from source for date ' . $date . ':', $result);
            return $result;
        } catch (\Exception $e) {
            \Log::error('Error fetching First Dry data from source: ' . $e->getMessage());
            return [
                'TPL' => [
                    'whisker_target' => 0,
                    'whisker_production' => 0,
                    'handbrush_target' => 0,
                    'handbrush_production' => 0,
                    'firstdryfinal_target' => 0,
                    'firstdryfinal_production' => 0,
                    'firstdryfinal_defect' => 0
                ],
                'TWL' => [
                    'whisker_target' => 0,
                    'whisker_production' => 0,
                    'handbrush_target' => 0,
                    'handbrush_production' => 0,
                    'firstdryfinal_target' => 0,
                    'firstdryfinal_production' => 0,
                    'firstdryfinal_defect' => 0
                ]
            ];
        }
    }

    /**
     * Get data from Second Dry Process (ALWAYS from original source)
     * This is for the Create Form - users need to see source data to modify
     */
    private function getSecondDryProcessDataFromSource($date)
    {
        try {
            $result = [
                'TPL' => [
                    'laser_target' => 0,
                    'laser_production' => 0,
                    'ppspray_target' => 0,
                    'ppspray_production' => 0,
                    'seconddryfinal_target' => 0,
                    'seconddryfinal_production' => 0,
                    'seconddryfinal_defect' => 0
                ],
                'TWL' => [
                    'laser_target' => 0,
                    'laser_production' => 0,
                    'ppspray_target' => 0,
                    'ppspray_production' => 0,
                    'seconddryfinal_target' => 0,
                    'seconddryfinal_production' => 0,
                    'seconddryfinal_defect' => 0
                ]
            ];

            // ========== PART 1: GET LASER & PP SPRAY DATA (BOTH PLANTS) ==========
            // Laser
            $laserData = SecondDryProcessEntry::where('processType', 'Laser')
                ->where('date', $date)
                ->get();

            foreach ($laserData as $item) {
                if ($item->plant == 'TPL' || $item->plant == 'TWL') {
                    $result[$item->plant]['laser_target'] = (int)($item->TargetQty ?? 0);
                    $result[$item->plant]['laser_production'] = (int)($item->ProductionQty ?? 0);
                }
            }

            // PP Spray
            $ppSprayData = SecondDryProcessEntry::where('processType', 'PP Spray')
                ->where('date', $date)
                ->get();

            foreach ($ppSprayData as $item) {
                if ($item->plant == 'TPL' || $item->plant == 'TWL') {
                    $result[$item->plant]['ppspray_target'] = (int)($item->TargetQty ?? 0);
                    $result[$item->plant]['ppspray_production'] = (int)($item->ProductionQty ?? 0);
                }
            }

            // ========== PART 2: GET 2ND DRY FINAL DATA ==========
            // TPL from QC database
            $query = "
            SELECT
                fc.PlantName,
                SUM(fc.ProductionQty) AS ProductionQty,
                SUM(fc.DefectQty) AS DefectQty,
                SUM(ISNULL(t.DailyTarget, 0)) AS TargetQty
            FROM
            (
                SELECT 
                    p.PlantName,
                    pu.UnitName,
                    fq.QcDate,
                    fq.WashProcessId,
                    SUM(CASE WHEN fq.QcStatusId IN (1,3) THEN 1 ELSE 0 END) AS ProductionQty,
                    SUM(CASE WHEN fq.QcStatusId = 2 THEN 1 ELSE 0 END) AS DefectQty
                FROM [DHU_WashDB].[dbo].[FirstDryProcessQc] fq
                JOIN FirstDryProcess fp ON fq.FirstDryProcessId = fp.Id
                JOIN PlantUnit pu ON fp.UnitId = pu.id
                JOIN Plant p ON pu.PlantId = p.Id
                WHERE fq.IsDeleted = 0
                  AND fq.QcDate = ?
                GROUP BY 
                    p.PlantName,
                    pu.UnitName,
                    fq.QcDate,
                    fq.WashProcessId
            ) fc
            LEFT JOIN
            (
                SELECT 
                    wh.WorkingHourDay AS [Date],
                    whdpm.WashProcessId,
                    SUM(whdpm.DailyTarget) AS DailyTarget
                FROM WorkingHourDetailManPower whdpm
                JOIN WorkingHourDetail whd ON whdpm.WorkingHourDetailId = whd.Id
                JOIN WorkingHour wh ON whd.WorkingHourId = wh.Id
                WHERE wh.WorkingHourDay = ?
                GROUP BY 
                    wh.WorkingHourDay,
                    whdpm.WashProcessId
            ) t
                ON t.WashProcessId = fc.WashProcessId
                AND t.[Date] = fc.QcDate
            JOIN WashProcess wp 
                ON wp.Id = fc.WashProcessId
            WHERE wp.ProcessName = '2nd Dry Final'
            GROUP BY fc.PlantName
            ORDER BY fc.PlantName;
        ";

            $params = [$date, $date];
            $secondDryFinalResults = DB::connection('sqlsrv_second')->select($query, $params);

            // Parse TPL 2nd Dry Final results
            foreach ($secondDryFinalResults as $row) {
                if ($row->PlantName == 'TPL') {
                    $result['TPL']['seconddryfinal_target'] = (int)$row->TargetQty;
                    $result['TPL']['seconddryfinal_production'] = (int)$row->ProductionQty;
                    $result['TPL']['seconddryfinal_defect'] = (int)($row->DefectQty ?? 0);
                }
            }

            // TWL 2nd Dry Final from SecondDryProcessEntry
            $twlSecondDryFinalData = SecondDryProcessEntry::where('plant', 'TWL')
                ->where('processType', '2nd Dry Final')
                ->where('date', $date)
                ->first();

            if ($twlSecondDryFinalData) {
                $result['TWL']['seconddryfinal_target'] = (int)($twlSecondDryFinalData->TargetQty ?? 0);
                $result['TWL']['seconddryfinal_production'] = (int)($twlSecondDryFinalData->ProductionQty ?? 0);
                $result['TWL']['seconddryfinal_defect'] = (int)($twlSecondDryFinalData->defectQty ?? 0);
            }

            \Log::info('Second Dry Data fetched from source for date ' . $date . ':', $result);
            return $result;
        } catch (\Exception $e) {
            \Log::error('Error fetching Second Dry data from source: ' . $e->getMessage());
            return [
                'TPL' => [
                    'laser_target' => 0,
                    'laser_production' => 0,
                    'ppspray_target' => 0,
                    'ppspray_production' => 0,
                    'seconddryfinal_target' => 0,
                    'seconddryfinal_production' => 0,
                    'seconddryfinal_defect' => 0
                ],
                'TWL' => [
                    'laser_target' => 0,
                    'laser_production' => 0,
                    'ppspray_target' => 0,
                    'ppspray_production' => 0,
                    'seconddryfinal_target' => 0,
                    'seconddryfinal_production' => 0,
                    'seconddryfinal_defect' => 0
                ]
            ];
        }
    }

    /**
     * Get create form for modal with pre-populated data
     */
    public function createForm(Request $request)
    {
        $plants = ['TPL', 'TWL'];
        $selectedDate = $request->get('date', now()->toDateString());

        // Get data from ORIGINAL sources (QC database and SecondDryProcessEntry)
        // This ALWAYS fetches from the original query, regardless of date
        $firstDryData = $this->getFirstDryProcessDataFromSource($selectedDate);
        $secondDryData = $this->getSecondDryProcessDataFromSource($selectedDate);

        // Prepare default data for each plant - COMBINE both sources
        $defaultData = [
            'TPL' => [
                // First Dry data
                'whisker_target' => $firstDryData['TPL']['whisker_target'] ?? 0,
                'whisker_production' => $firstDryData['TPL']['whisker_production'] ?? 0,
                'handBrush_target' => $firstDryData['TPL']['handbrush_target'] ?? 0,
                'handBrush_production' => $firstDryData['TPL']['handbrush_production'] ?? 0,
                'FirstDryFinal_target' => $firstDryData['TPL']['firstdryfinal_target'] ?? 0,
                'FirstDryFinal_production' => $firstDryData['TPL']['firstdryfinal_production'] ?? 0,
                'FirstDryFinal_defectQty' => $firstDryData['TPL']['firstdryfinal_defect'] ?? 0,

                // Second Dry data
                'SecondDryFinal_target' => $secondDryData['TPL']['seconddryfinal_target'] ?? 0,
                'SecondDryFinal_production' => $secondDryData['TPL']['seconddryfinal_production'] ?? 0,
                'SecondDryFinal_defectQty' => $secondDryData['TPL']['seconddryfinal_defect'] ?? 0,
            ],
            'TWL' => [
                // First Dry data
                'whisker_target' => $firstDryData['TWL']['whisker_target'] ?? 0,
                'whisker_production' => $firstDryData['TWL']['whisker_production'] ?? 0,
                'handBrush_target' => $firstDryData['TWL']['handbrush_target'] ?? 0,
                'handBrush_production' => $firstDryData['TWL']['handbrush_production'] ?? 0,
                'FirstDryFinal_target' => $firstDryData['TWL']['firstdryfinal_target'] ?? 0,
                'FirstDryFinal_production' => $firstDryData['TWL']['firstdryfinal_production'] ?? 0,
                'FirstDryFinal_defectQty' => $firstDryData['TWL']['firstdryfinal_defect'] ?? 0,

                // Second Dry data
                'SecondDryFinal_target' => $secondDryData['TWL']['seconddryfinal_target'] ?? 0,
                'SecondDryFinal_production' => $secondDryData['TWL']['seconddryfinal_production'] ?? 0,
                'SecondDryFinal_defectQty' => $secondDryData['TWL']['seconddryfinal_defect'] ?? 0,
            ]
        ];

        \Log::info('Combined Default Data for Create Form:', $defaultData);

        return view('backend.dry-process-manual.create', compact('plants', 'selectedDate', 'defaultData'));
    }

    /**
     * Get edit form for modal with saved data
     */
    public function edit($id)
    {
        $dryProcessManual = DryProcessManual::findOrFail($id);
        $plants = ['TPL', 'TWL'];

        return view('backend.dry-process-manual.edit', compact('dryProcessManual', 'plants'));
    }

    /**
     * Store new record
     */
    public function store(Request $request)
    {
        \Log::info('Store request data:', $request->all());

        // Dynamic validation based on plant
        $rules = [
            'plantName' => 'required|in:TPL,TWL',
            'date' => 'required|date',
            'FirstDryFinal_target' => 'required|integer|min:0',
            'FirstDryFinal_production' => 'required|integer|min:0',
            'FirstDryFinal_defectQty' => 'nullable|integer|min:0',
            'SecondDryFinal_target' => 'required|integer|min:0',
            'SecondDryFinal_production' => 'required|integer|min:0',
            'SecondDryFinal_defectQty' => 'nullable|integer|min:0',
        ];

        // Add plant-specific rules
        if ($request->plantName === 'TWL') {
            $rules['whisker_target'] = 'required|integer|min:0';
            $rules['whisker_production'] = 'required|integer|min:0';
            $rules['handBrush_target'] = 'required|integer|min:0';
            $rules['handBrush_production'] = 'required|integer|min:0';
        } else {
            // For TPL, these fields are not required but should default to 0
            $rules['whisker_target'] = 'nullable|integer|min:0';
            $rules['whisker_production'] = 'nullable|integer|min:0';
            $rules['handBrush_target'] = 'nullable|integer|min:0';
            $rules['handBrush_production'] = 'nullable|integer|min:0';
        }

        $validator = Validator::make($request->all(), $rules);

        // Make defect quantities required when production > 0 for final processes
        $validator->after(function ($validator) use ($request) {
            if ($request->FirstDryFinal_production > 0 && !$request->filled('FirstDryFinal_defectQty')) {
                $validator->errors()->add('FirstDryFinal_defectQty', 'Defect quantity is required when production quantity is greater than 0 for First Dry Final.');
            }

            if ($request->SecondDryFinal_production > 0 && !$request->filled('SecondDryFinal_defectQty')) {
                $validator->errors()->add('SecondDryFinal_defectQty', 'Defect quantity is required when production quantity is greater than 0 for Second Dry Final.');
            }
        });

        if ($validator->fails()) {
            \Log::error('Validation failed:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check if record already exists for this plant and date
            $existing = DryProcessManual::where('plantName', $request->plantName)
                ->where('date', $request->date)
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'date' => ['A record already exists for this plant and date. Please edit the existing record.']
                    ]
                ], 422);
            }

            // Prepare data - set default values for TPL fields
            $data = [
                'date' => $request->date,
                'plantName' => $request->plantName,
                'whisker_target' => $request->whisker_target ?? 0,
                'whisker_production' => $request->whisker_production ?? 0,
                'handBrush_target' => $request->handBrush_target ?? 0,
                'handBrush_production' => $request->handBrush_production ?? 0,
                'FirstDryFinal_target' => $request->FirstDryFinal_target,
                'FirstDryFinal_production' => $request->FirstDryFinal_production,
                'FirstDryFinal_defectQty' => $request->FirstDryFinal_defectQty ?? 0,
                'SecondDryFinal_target' => $request->SecondDryFinal_target,
                'SecondDryFinal_production' => $request->SecondDryFinal_production,
                'SecondDryFinal_defectQty' => $request->SecondDryFinal_defectQty ?? 0,
            ];

            \Log::info('Data to insert:', $data);

            DryProcessManual::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Dry Process Manual entry created successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating record: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update record
     */
    public function update(Request $request, $id)
    {
        \Log::info('Update request data for ID ' . $id . ':', $request->all());

        // Dynamic validation based on plant
        $rules = [
            'plantName' => 'required|in:TPL,TWL',
            'date' => 'required|date',
            'FirstDryFinal_target' => 'required|integer|min:0',
            'FirstDryFinal_production' => 'required|integer|min:0',
            'FirstDryFinal_defectQty' => 'nullable|integer|min:0',
            'SecondDryFinal_target' => 'required|integer|min:0',
            'SecondDryFinal_production' => 'required|integer|min:0',
            'SecondDryFinal_defectQty' => 'nullable|integer|min:0',
        ];

        // Add plant-specific rules
        if ($request->plantName === 'TWL') {
            $rules['whisker_target'] = 'required|integer|min:0';
            $rules['whisker_production'] = 'required|integer|min:0';
            $rules['handBrush_target'] = 'required|integer|min:0';
            $rules['handBrush_production'] = 'required|integer|min:0';
        } else {
            // For TPL, these fields are not required
            $rules['whisker_target'] = 'nullable|integer|min:0';
            $rules['whisker_production'] = 'nullable|integer|min:0';
            $rules['handBrush_target'] = 'nullable|integer|min:0';
            $rules['handBrush_production'] = 'nullable|integer|min:0';
        }

        $validator = Validator::make($request->all(), $rules);

        // Make defect quantities required when production > 0 for final processes
        $validator->after(function ($validator) use ($request) {
            if ($request->FirstDryFinal_production > 0 && !$request->filled('FirstDryFinal_defectQty')) {
                $validator->errors()->add('FirstDryFinal_defectQty', 'Defect quantity is required when production quantity is greater than 0 for First Dry Final.');
            }

            if ($request->SecondDryFinal_production > 0 && !$request->filled('SecondDryFinal_defectQty')) {
                $validator->errors()->add('SecondDryFinal_defectQty', 'Defect quantity is required when production quantity is greater than 0 for Second Dry Final.');
            }
        });

        if ($validator->fails()) {
            \Log::error('Validation failed:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $dryProcessManual = DryProcessManual::findOrFail($id);

            // Check if another record exists for this plant and date (excluding current)
            $existing = DryProcessManual::where('plantName', $request->plantName)
                ->where('date', $request->date)
                ->where('id', '!=', $id)
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'date' => ['Another record already exists for this plant and date.']
                    ]
                ], 422);
            }

            // Prepare data - set default values for TPL fields
            $data = [
                'date' => $request->date,
                'plantName' => $request->plantName,
                'whisker_target' => $request->whisker_target ?? 0,
                'whisker_production' => $request->whisker_production ?? 0,
                'handBrush_target' => $request->handBrush_target ?? 0,
                'handBrush_production' => $request->handBrush_production ?? 0,
                'FirstDryFinal_target' => $request->FirstDryFinal_target,
                'FirstDryFinal_production' => $request->FirstDryFinal_production,
                'FirstDryFinal_defectQty' => $request->FirstDryFinal_defectQty ?? 0,
                'SecondDryFinal_target' => $request->SecondDryFinal_target,
                'SecondDryFinal_production' => $request->SecondDryFinal_production,
                'SecondDryFinal_defectQty' => $request->SecondDryFinal_defectQty ?? 0,
            ];

            \Log::info('Data to update:', $data);

            $dryProcessManual->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Dry Process Manual entry updated successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating record: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete record
     */
    public function delete($id)
    {
        try {
            $dryProcessManual = DryProcessManual::findOrFail($id);
            $dryProcessManual->delete();

            return response()->json([
                'success' => true,
                'message' => 'Record deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting record: ' . $e->getMessage()
            ], 500);
        }
    }
}