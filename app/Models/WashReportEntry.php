<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WashReportEntry extends Model
{
    use HasFactory;

    protected $table = "washReportEntry";

    protected $fillable = [
        'date',
        'unit',
        'FirstWashQty',
        'AcidWashQty',
        'FinalWashQty',
        'ReWashQty',
        'SewingLine',
        'Remarks',
        'rework_dry_proc',
        'in_hand_balance',
        'machine_work_hr',
    ];

    protected $casts = [
        'date' => 'date',
        'FirstWashQty' => 'integer',
        'AcidWashQty' => 'integer',
        'FinalWashQty' => 'integer',
        'ReWashQty' => 'integer',
        'machine_work_hr' => 'float',
    ];

    // Accessor for total quantity
    public function getTotalQtyAttribute()
    {
        return $this->FirstWashQty + $this->AcidWashQty + $this->FinalWashQty + $this->ReWashQty;
    }
}