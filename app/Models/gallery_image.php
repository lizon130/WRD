<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gallery_image extends Model
{
    use HasFactory;
    protected $fillable = [
        'gallery_events_id',
        'name',
        'image',
    ];
    
}
