<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlumniSection extends Model
{
    use HasFactory;
    protected $table = 'alumni_sections';
    public $fillable =[
        'key',
        'value',
        'status'
    ];

}
