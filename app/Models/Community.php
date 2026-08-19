<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;
    protected $table = 'communities';

    protected $fillable = [
    'alumni_id',
    'member_id',
    'description',
    'from_year',
    'to_year',
    'responsibility',
    'type',
];
}
