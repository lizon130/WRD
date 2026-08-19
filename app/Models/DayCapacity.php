<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DayCapacity extends Model
{
    use HasFactory;

    protected $table = 'day_capacities';

    protected $fillable = [
        'date',
        'unit_id',
        'hour_00', 'hour_01', 'hour_02', 'hour_03', 'hour_04', 'hour_05',
        'hour_06', 'hour_07', 'hour_08', 'hour_09', 'hour_10', 'hour_11',
        'hour_12', 'hour_13', 'hour_14', 'hour_15', 'hour_16', 'hour_17',
        'hour_18', 'hour_19', 'hour_20', 'hour_21', 'hour_22', 'hour_23',
        'total_initial_target',
        'total_mg_target'
    ];

    protected $casts = [
        'date' => 'date',
        'hour_00' => 'decimal:2', 'hour_01' => 'decimal:2', 'hour_02' => 'decimal:2', 'hour_03' => 'decimal:2',
        'hour_04' => 'decimal:2', 'hour_05' => 'decimal:2', 'hour_06' => 'decimal:2', 'hour_07' => 'decimal:2',
        'hour_08' => 'decimal:2', 'hour_09' => 'decimal:2', 'hour_10' => 'decimal:2', 'hour_11' => 'decimal:2',
        'hour_12' => 'decimal:2', 'hour_13' => 'decimal:2', 'hour_14' => 'decimal:2', 'hour_15' => 'decimal:2',
        'hour_16' => 'decimal:2', 'hour_17' => 'decimal:2', 'hour_18' => 'decimal:2', 'hour_19' => 'decimal:2',
        'hour_20' => 'decimal:2', 'hour_21' => 'decimal:2', 'hour_22' => 'decimal:2', 'hour_23' => 'decimal:2',
        'total_initial_target' => 'decimal:2',
        'total_mg_target' => 'decimal:2'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}