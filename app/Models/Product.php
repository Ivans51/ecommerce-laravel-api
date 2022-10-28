<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class Product extends Model
{
    use HasUuid;
    use HasFactory;
    use SoftDeletes;

    public $incrementing = false;
    public $keyType = 'string';
    protected string $uuidColumnName = 'id';

    protected $fillable = [
        'name',
        'desc',
        'SKU',
        'price',
        'category_id',
        'inventory_id',
        'discount_id',
    ];
}
