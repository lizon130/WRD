<?php

namespace App\Models;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory;
    protected $table = 'teachers';
    protected $fillable = [
        'user_id',
        'teacher_id',
        'specialization',
        'designation',
        'birthdate',
        'gender',
        'status',
    ];

    // In Teacher.php
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
    // public function getimageAttribute($value)
    // {
    //     if (!empty($value)) {
            
    //         if (request()->is('api/*')) {
    //             return url($value);
    //         }
    //         return $value;
    //     }
    //     return $value;
    // }
}
