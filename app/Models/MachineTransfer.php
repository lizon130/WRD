<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineTransfer extends Model
{
    use HasFactory;

    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;

    protected $table = 'machine_transfers';

    protected $fillable = [
        'transfer_date',
        'from_unit_id',
        'to_unit_id',
        'machine_count',
        'hours',
        'from_unit_machine_count_before',
        'from_unit_machine_count_after',
        'to_unit_machine_count_before',
        'to_unit_machine_count_after',
        'from_unit_mg_target_before',
        'from_unit_mg_target_after',
        'to_unit_mg_target_before',
        'to_unit_mg_target_after',
        'from_unit_capacity_kg_before',
        'from_unit_capacity_kg_after',
        'to_unit_capacity_kg_before',
        'to_unit_capacity_kg_after',
        'from_unit_capacity_pieces_before',
        'from_unit_capacity_pieces_after',
        'to_unit_capacity_pieces_before',
        'to_unit_capacity_pieces_after',
        'calculated_production',
        'status',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'rejection_reason'
    ];

    protected $casts = [
        'transfer_date' => 'date',
        'machine_count' => 'integer',
        'hours' => 'integer',
        'from_unit_machine_count_before' => 'integer',
        'from_unit_machine_count_after' => 'integer',
        'to_unit_machine_count_before' => 'integer',
        'to_unit_machine_count_after' => 'integer',
        'from_unit_mg_target_before' => 'decimal:2',
        'from_unit_mg_target_after' => 'decimal:2',
        'to_unit_mg_target_before' => 'decimal:2',
        'to_unit_mg_target_after' => 'decimal:2',
        'from_unit_capacity_kg_before' => 'decimal:2',
        'from_unit_capacity_kg_after' => 'decimal:2',
        'to_unit_capacity_kg_before' => 'decimal:2',
        'to_unit_capacity_kg_after' => 'decimal:2',
        'from_unit_capacity_pieces_before' => 'decimal:2',
        'from_unit_capacity_pieces_after' => 'decimal:2',
        'to_unit_capacity_pieces_before' => 'decimal:2',
        'to_unit_capacity_pieces_after' => 'decimal:2',
        'calculated_production' => 'decimal:2',
        'status' => 'integer',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime'
    ];

    public function fromUnit()
    {
        return $this->belongsTo(Unit::class, 'from_unit_id');
    }

    public function toUnit()
    {
        return $this->belongsTo(Unit::class, 'to_unit_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejector()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    // Status helpers
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case self::STATUS_APPROVED:
                return 'Approved';
            case self::STATUS_REJECTED:
                return 'Rejected';
            default:
                return 'Pending';
        }
    }

    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case self::STATUS_APPROVED:
                return '<span class="badge bg-success">Approved</span>';
            case self::STATUS_REJECTED:
                return '<span class="badge bg-danger">Rejected</span>';
            default:
                return '<span class="badge bg-warning">Pending</span>';
        }
    }

    // Scope for pending transfers
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }
}

