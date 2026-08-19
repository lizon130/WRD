<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public $fillable =[
        'key',
        'value',
        'status'
    ];
    // public function getimageAttribute($value)
    // {
    //     if (!empty($value)) {

    //         if (request()->is('api/*')) {
    //             return url($value);
    //         }
    //         return $value;
    //     }
    //     return $value;
    // }

}
