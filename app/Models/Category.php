<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static latest()
 * @method static insert(array $array)
 * @method static findOrFail($id)
 * @method static orderBy()
 * @method static skip(int $int)
 */
class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'category_name_en',
        'category_name_vn',
        'category_slug_en',
        'category_slug_vn',
        'category_icon',
    ];
}
