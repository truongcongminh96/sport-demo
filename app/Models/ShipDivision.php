<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static orderBy(string $string, string $string1)
 * @method static insert(array $array)
 * @method static findOrFail($id)
 */
class ShipDivision extends Model
{
    use HasFactory;

    protected $guarded = [];
}
