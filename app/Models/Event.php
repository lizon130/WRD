<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $table = 'events';
    
    protected $fillable = [
        'name',
        'sub_nameor_title',
        'details',
        'image',
        'event_images',
        'location',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'speakers_info',
        'workshops_info',
        'networks_info',
        'status',
        // 'is_upcoming',
        'is_featured',
        'published_user'
        
    ];

    // public function getimageAttribute($value)
    // {
    //     if (!empty($value)) {
    //         // Check if the request is from the API
    //         if (request()->is('api/*')) {
    //             return url($value); // Return full URL
    //         }
    //         return $value; // Return relative path
    //     }
    //     return $value; // Return original value if empty
    // }
}

