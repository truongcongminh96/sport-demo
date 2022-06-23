<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static insert(array $array)
 * @method static findOrFail($id)
 */
class Coupon extends Model
{
    use HasFactory;

    protected $guarded = [];
}
