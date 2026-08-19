<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory;

    protected $table = 'company';
    protected $primaryKey = 'company_id';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->company_id = substr(uniqid(), 0, 13).'-comp-'.random_int(10000000000000000, 99999999999999999);
        });
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
