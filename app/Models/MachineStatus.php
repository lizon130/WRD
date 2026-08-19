<?php
// app/Models/MachineStatus.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachineStatus extends Model
{
    protected $fillable = [
        'unit',
        'plant',
        'machine_group',
        'uptime',
        'idletime',
        'downtime',
        'up_duration',
        'idle_duration',
        'down_duration',
        'report_date',
        'fetched_at'
    ];

    protected $casts = [
        'uptime' => 'decimal:2',
        'idletime' => 'decimal:2',
        'downtime' => 'decimal:2',
        'report_date' => 'date',
        'fetched_at' => 'datetime'
    ];

    public static function getLatestForUnit($unit, $date = null)
    {
        $query = self::where('unit', $unit);
        
        if ($date) {
            $query->where('report_date', $date);
        } else {
            $query->latest('report_date');
        }
        
        return $query->first();
    }

    public static function getSummaryForDate($date = null)
    {
        $date = $date ?? now()->toDateString();
        
        return self::where('report_date', $date)
            ->get()
            ->groupBy('unit')
            ->map(function ($group) {
                return [
                    'uptime' => round($group->avg('uptime'), 2),
                    'idletime' => round($group->avg('idletime'), 2),
                    'downtime' => round($group->avg('downtime'), 2),
                ];
            });
    }
}