<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DryProcessIE extends Model
{
    use HasFactory;

    // Table name (optional if model name matches table name)
    protected $table = 'dryProcessIE';

    // Primary key (optional if 'id')
    protected $primaryKey = 'id';

    // Mass assignable fields
    protected $fillable = [
        'plant',
        'date',
        'processType',
        'manPower',
        'workingHr',
        'smv',
    ];

    // Timestamps enabled
    public $timestamps = true;
}