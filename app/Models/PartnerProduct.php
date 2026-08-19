<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerProduct extends Model
{
    use HasFactory;

    protected $table = 'company_product';
    protected $fillable = ['company_id', 'partner', 'category_id', 'subcategory_id', 'product_id', 'quantity', 'price', 'discount_type', 'discount_price', 'status'];

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function sub_category(){
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
}
