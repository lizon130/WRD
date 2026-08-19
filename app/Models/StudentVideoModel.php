<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentVideoModel extends Model
{
    use HasFactory;
    protected $table = 'student_videos';
    public $fillable =[
        'title',
        'video_link',
        'image',
        'status',
    ];
}
