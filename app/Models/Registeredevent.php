<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisteredEvent extends Model
{
    use HasFactory;
    protected $table = "registeredevents";
    protected $fillable = [
        'event_id',
        'user_id',
        'name',
        'email',
        'phone',
        'gender',
        'present_address',
        'permanent_address',
        'grade',
        'school',
        'studentId',
        'interests',
        'participation',
        'diet',
        'notes',
        'terms',
    ];
}
