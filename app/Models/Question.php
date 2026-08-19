<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $table = 'questions';
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($question) {
            // Delete related options
            $question->options()->delete();
        });
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function options(){
        return $this->hasMany(QuestionOption::class, 'question_id', 'id');
    }
}
