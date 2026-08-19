<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = uniqid().'-ctgr-'.random_int(10000000000000000, 99999999999999999);
        });
    }

    public function parent(){
        return $this->belongsTo(Category::class, 'parent_category', 'id');
    }

    public function subcategories(){
        return $this->hasMany(Category::class, 'parent_category', 'id');
    }

    public function rootParent()
    {
        return $this->parent ? $this->parent->rootParent() : $this;
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function subcategoriesRecursive()
    {
        return $this->hasMany(Category::class, 'parent_category', 'id')->with('subcategoriesRecursive');
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
