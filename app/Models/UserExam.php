<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserExam extends Model
{
    use HasFactory;

    protected $table = 'user_exams';

    protected $fillable = [
        'user_id',
        'ip_address',
        'name',
        'phone',
        'email',
        'organization',
        'achieve_mark',
        'negative_mark',
        'total_mark',
        'total_duration',
        'exam_id',
        'correct_answers',
        'wrong_answers',
        'started_at',
        'ended_at',
        'status',
        'result_published',
        'position'
    ];

    public function answers(){
        return $this->hasMany(UserExamAnswer::class, 'result_id', 'id');
    }

    public function exam(){
        return $this->belongsTo(Exam::class, 'exam_id', 'id');
    }
}
