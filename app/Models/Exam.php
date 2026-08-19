<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; 

class Exam extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) { // Generate only if slug is not already set
                $model->slug = static::generateSlug($model->name);
            }
        });
    
        static::updating(function ($model) {
            if (empty($model->slug) || $model->isDirty('name')) { // Check if slug is empty or name is changed
                $model->slug = static::generateSlug($model->name);
            }
        });
    }

    protected static function generateSlug($name)
    {
        return Str::slug($name, '-');
    }

    public function questions(){
        return $this->hasMany(ExamQuestion::class, 'exam_id', 'id');
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function examtype(){
        return $this->belongsTo(CustomOption::class, 'type', 'id')->where('option_for', 'exam');
    }
}
