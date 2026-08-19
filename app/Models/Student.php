<?php

namespace App\Models;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'student_id',
        'class',
        'group',
        'birthdate',
        'father_name',
        'village',
        'post_office',
        'upazila',
        'district',
        'roll',
        'section',
        'gender',
        'blood_grp',
        'nationality',
        'language',
        'current_school',
        'previous_school',
        'father_phone',
        'mother_name',
        'local_guardian_name',
        'local_guardian_phone',
        'emergency_phone',
        'current_village',
        'current_post_office',
        'current_district',
        'current_upazila',
        'hobbie',
        'alternative_phone',
        'education_year',
        'location',
        'mother_phone',
        'profile_image',
    ];

    // In Student.php
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }


}
