<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserExamAnswer extends Model
{
    use HasFactory;

    protected $table = 'user_exam_result_answers';

    protected $fillable = [
        'result_id',
        'question_id',
        'answear',
        'right_wrong',
        'marks',
        'status',
    ];

    public function question(){
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }
}
