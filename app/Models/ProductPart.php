<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPart extends Model
{
    use HasFactory;
    protected $table = 'product_part';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = substr(uniqid(), 0, 13).'-pprt-'.random_int(10000000000000000, 99999999999999999);
        });
    }

    public function brand(){
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    
    public function attributes(){
        return $this->hasMany(PartAttribute::class, 'part_id')->where('type', 'attributes');
    }

    public function custom_options(){
        return $this->hasMany(PartAttribute::class, 'part_id')->where('type', 'custom value');
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
