<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class ProductCategory extends Model
{
    use HasUuid;
    use HasFactory;

    public $incrementing = false;
    public $keyType = 'string';
    protected string $uuidColumnName = 'id';

    protected $fillable = [
        'name',
        'desc',
    ];
}