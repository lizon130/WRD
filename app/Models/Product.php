<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    protected $table = 'product';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = substr(uniqid(), 0, 13).'-prod-'.random_int(10000000000000000, 99999999999999999);
            $model->generateSlug();
        });
		
		static::deleting(function ($product) {
            // Delete related attributes
            $product->attributes()->delete();

            // Delete related custom options
            $product->custom_options()->delete();

            // Delete related services
            $product->servies()->delete();

            // Delete related parts
            $product->parts()->delete();

            // Delete related translations
            $product->translations()->delete();
        });
    }

    public function brand(){
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function sub_category(){
        return $this->belongsTo(Category::class, 'sub_category_id');
    }

    public function attributes(){
        return $this->hasMany(ProductAttribute::class, 'product_id')->where('type', 'attributes');
    }

    public function custom_options(){
        return $this->hasMany(ProductAttribute::class, 'product_id')->where('type', 'custom value');
    }

    public function servies(){
        return $this->hasMany(Service::class, 'product_id', 'id');
    }

    public function parts(){
        return $this->hasMany(ProductPart::class, 'product_id', 'id');
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
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

    protected function generateSlug(){
		if(optional($this->brand)->title){
			$brand = $this->brand->title;
		}else{
			$brand = 'other';
		}
        $slug = Str::slug($this->code.'-'.$brand.'-'.$this->name);

        $count = static::where('slug', $slug)->where('id', '!=', $this->id)->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }
        $this->slug = $slug;
    }
}
