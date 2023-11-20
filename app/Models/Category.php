<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'category';

    protected $fillable = [
        'category_name',
        'parent_category_id',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'productcategory');
    }

    /* not sure about these two */
    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }

    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_category_id');
    }
}
