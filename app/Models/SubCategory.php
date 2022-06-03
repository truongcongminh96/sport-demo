<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static latest()
 * @method static findOrFail($id)
 */
class SubCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'category_id',
        'subcategory_name_en',
        'subcategory_name_vn',
        'subcategory_slug_en',
        'subcategory_slug_vn'
    ];

    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
