<?php
// app/Models/DryerProcessManual.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DryerProcessManual extends Model
{
    use HasFactory;

    protected $table = 'dryer_process_manual';

    protected $fillable = [
        'TransactionDate',
        'unit',
        'processStageName',
        'totalQty',
    ];

    protected $casts = [
        'TransactionDate' => 'date',
        'totalQty' => 'float',
    ];
}