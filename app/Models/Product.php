<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'vendor_id',
        'category_id',
        'price',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'sub_category_id',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }
    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id', 'id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function colorSizes()
    {
        return $this->hasMany(ProductColorSize::class);
    }

}
