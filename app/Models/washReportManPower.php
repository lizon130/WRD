<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WashReportManPower extends Model
{
    use HasFactory;

    protected $table = "washReportManPower";

    protected $fillable = [
        'date',
        'unit',
        'direct',
        'indirect',
        'work_hours',
        'smv',
    ];

    protected $casts = [
        'direct' => 'integer',
        'indirect' => 'integer',
        'work_hours' => 'decimal:2',
        'smv' => 'decimal:2',
        'date' => 'date',
    ];
}