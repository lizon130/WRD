<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $table = 'contact';
    // protected $fillable = [
    //     'name', 
    //     'email', 
    //     'phone', 
    //     'message', 
    //     'status'
    // ];
    
   protected $fillable = [
    'name',
    'title',
    'email',
    'subject',
    'message',
    'toll_free',
    'fax',
    'address',
    'google_map',
    'status',
    'is_default',
    'user_id',
];

}
