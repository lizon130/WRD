<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;
    protected $table = 'classes';
    protected $fillable = [
        'name',
        'short_description',
        'long_description',
        'image',
        'duration',
        'fees',
        'requiredments',
        'subject',
        'teacher',
        'status',
    ];

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
