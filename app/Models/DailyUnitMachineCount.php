<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyUnitMachineCount extends Model
{
    use HasFactory;

    protected $table = 'daily_unit_machine_counts';

    protected $fillable = [
        'date',
        'unit_id',
        'machine_count'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
