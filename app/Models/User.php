<?php

namespace App\Models;

use App\Models\Profile;
use App\Models\Student;
use App\Models\Teacher;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = "user";
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'role',
        'status',
        'profile_image',
        'banner',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    // Add the following methods required by JWTSubject
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Primary key (usually 'id')
    }

    public function getJWTCustomClaims()
    {
        return []; // Add custom claims if needed
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->id = substr(uniqid(), 0, 13).'-user-'.random_int(10000000000000000, 99999999999999999);
    //     });
    // }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Get the maximum numeric ID from database
            $maxId = self::max('id') ?? 0;
            
            // If maxId is not numeric (has old string IDs), find max numeric
            if (!is_numeric($maxId)) {
                $maxId = self::whereRaw('id REGEXP "^[0-9]+$"')
                    ->max(DB::raw('CAST(id AS UNSIGNED)')) ?? 0;
            }
            
            $model->id = $maxId + 1;
        });
    }

    public function roles(){
        return $this->belongsTo(Role::class, 'role', 'id');
    }

    public function company(){
        return $this->hasOne(Company::class, 'user_id', 'id');
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    // Relationship with Student (One-to-One)
    public function student()
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    // Relationship with Teacher (One-to-One)
    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'user_id');
    }

    // public function getProfileImageAttribute($value)
    // {
    //     if (!empty($value)) {
    //         if (request()->is('api/*')){
    //             return url($value);
    //         }
    //     }
    //     return null;
    // }
}
