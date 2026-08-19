<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = "unit";
    use HasFactory;
    protected $fillable = [
        'unitName',
        'machineCount',
        'initialTarget',
        'mgTarget',
        'capacity_kg',
        'capacity_pieces',
        'piece_weight_gram',
        'sewing_lines'
    ];

    protected $casts = [
        'machineCount' => 'integer',
        'initialTarget' => 'decimal:2',
        'mgTarget' => 'decimal:2',
        'piece_weight_gram' => 'decimal:3'
    ];
}
