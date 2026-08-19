<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gallery_event extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_name',
        'image',
    ];
}
