<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static insert(array $array)
 * @method static insertGetId(array $array)
 */
class Product extends Model
{
    use HasFactory;

    protected $guarded = [];
}
