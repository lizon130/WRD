<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampusGallary extends Model
{
    use HasFactory;
    protected $table = 'campus_galleries';
    protected $fillable = [
        'title',
        'description',
        'short_description',
        'image',
        'thumbnail',
        'status',
        'static_status',
        'created_at',
        'updated_at',
    ];
}
