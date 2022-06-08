<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static latest()
 * @method static insert(array $array)
 * @method static findOrFail($brandId)
 * @method static where(string $string, int $int)
 */
class Slider extends Model
{
    use HasFactory;

    protected $guarded = [];
}
