<?php
// app/Models/DryProcessManual.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DryProcessManual extends Model
{
    use HasFactory;

    protected $table = 'dry_process_manual';

    protected $fillable = [
        'date',
        'plantName',
        'whisker_target',
        'whisker_production',
        'handBrush_target',
        'handBrush_production',
        'FirstDryFinal_target',
        'FirstDryFinal_production',
        'FirstDryFinal_defectQty',
        'SecondDryFinal_target',
        'SecondDryFinal_production',
        'SecondDryFinal_defectQty',
        'remarks',
        'remarks2'
    ];

    protected $casts = [
        'date' => 'date'
    ];
}