<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';
    
    protected $fillable = [
        'dept_name',
        'description',
        'image_url',
        'dept_gallary_images',
        'status',
        'created_at',
        'updated_at',
    ];

}
