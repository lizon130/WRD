<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondDryProcessEntry extends Model
{
    use HasFactory;

    // Table name (optional if it follows Laravel naming convention)
    protected $table = 'secondDryProcessEntry';

    // Primary key (optional if 'id')
    protected $primaryKey = 'id';

    // Mass assignable fields
    protected $fillable = [
        'plant',
        'date',
        'processType',
        'TargetQty',
        'ProductionQty',
        'rework_dry_proc',
        'defectQty'
    ];

    // Automatically manage timestamps
    public $timestamps = true;

    // Optional: if you want to cast types
    protected $casts = [
        'date' => 'date',
        'TargetQty' => 'integer',
        'ProductionQty' => 'integer',
    ];
}