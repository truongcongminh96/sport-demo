<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static truncate()
 * @method static create(array $array)
 * @method static findOrFail($seoId)
 */
class Seo extends Model
{
    use HasFactory;
    protected $guarded = [];
}
