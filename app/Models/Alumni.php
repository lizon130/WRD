<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    use HasFactory;
    protected $table = 'alumnis';

    protected $fillable = [
        'name',
        'email',
        'phone_no',
        'batch',
        'passing_year',
        'degree',
        'current_profession',
        'job_title',
        'company_name',
        'image',
        'linkedin_url',
        'transaction_id',
        'payment_method',
    ];

}
