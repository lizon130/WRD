<?php

// Profile.php
namespace App\Models;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'banner_image',
        'designation',
        'location',
        'about',
    ];

    // Define the relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Correct relationship with Student model
    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id', 'user_id'); // student is related through 'user_id'
    }

    // Correct relationship with Teacher model
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'user_id', 'user_id'); // teacher is related through 'user_id'
    }

    // public function getBannerImageAttribute($value)
    // {
    //     // Check if the value exists and is not empty
    //     if (!empty($value)) {
    //         if (request()->is('api/*')){
    //             return url($value);
    //         } 
    //     }
    //     return null;
    // }
}

