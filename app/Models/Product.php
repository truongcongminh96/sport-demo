<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static insert(array $array)
 * @method static insertGetId(array $array)
 * @method static latest()
 * @method static findOrFail($id)
 * @method static where(string $string, int $int)
 */
class Product extends Model
{
    use HasFactory;

    protected $guarded = [];
}