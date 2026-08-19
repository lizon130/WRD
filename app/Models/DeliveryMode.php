<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryMode extends Model
{
    use HasFactory;

    protected $table = 'delivery_mode';

    protected static function boot()
    {
        parent::boot();

        
        static::deleting(function ($qualification) {
            // Recursively delete all children segments
            $qualification->translations()->delete();
        });
    }

    public function translations()
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    public function getTranslation($languageCode, $field)
    {
        return $this->translations()
            ->where('language_code', $languageCode)
            ->where('field', $field)
            ->value('value') ?? $this->$field;
    }
}
