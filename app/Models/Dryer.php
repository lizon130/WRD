<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Dryer extends Model
{
    use HasFactory;

    protected $table = 'dryers';

    protected $fillable = [
        'unit',
        'num_dryer',
        'date',
        'capacity',
        'avg_dryer_time',
        'avg_batch',
        'working_hr',
        'targetQty',
        'first_wash_dryer',
        'cold_dryer',
        'measurement_correction',
        'final_wash_dryer',
    ];

    protected $casts = [
        'capacity' => 'decimal:2',
        'avg_dryer_time' => 'decimal:2',
        'avg_batch' => 'decimal:2',
        'working_hr' => 'decimal:2',
        'targetQty' => 'decimal:2',
        'first_wash_dryer' => 'decimal:2',
        'cold_dryer' => 'decimal:2',
        'measurement_correction' => 'decimal:2',
        'final_wash_dryer' => 'decimal:2',
    ];

    // Unit to dryer count mapping
    public static function getUnitDryerCount($unit)
    {
        $unitCounts = [
            'Unit 1' => 8,
            'Unit 2' => 7,
            'Unit 3' => 7,
            'Unit 4' => 5,
            'Unit 5' => 4,
            'Unit TWL' => 9,
        ];

        return $unitCounts[$unit] ?? 0;
    }

    // Get all available units
    public static function getAvailableUnits()
    {
        return ['Unit 1', 'Unit 2', 'Unit 3', 'Unit 4', 'Unit 5', 'Unit TWL'];
    }

    // Get the static values for a unit (from the most recent entry)
    public static function getStaticValuesForUnit($unit)
    {
        return self::where('unit', $unit)
            ->orderBy('date', 'desc')
            ->first();
    }
}