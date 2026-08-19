<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = substr(uniqid(), 0, 13).'-ordr-'.random_int(10000000000000000, 99999999999999999);
        });
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function details(){
        return $this->hasMany(OrderDetail::class);
    }
}
