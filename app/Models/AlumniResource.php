<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request; 

class AlumniResource extends Model
{
    use HasFactory;

    protected $table ='alumni_resource';

    public function getImageAttribute($value)
    {
        // Check if the image field is not empty and the request comes from API
        if (!empty($value) && Request::is('api/*')) {
            return url('uploads/resource-images/' . $value); // Ensure the slash is present
        }

        return $value; // Return the original value if empty
    }

}
