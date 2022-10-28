<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class CartItem extends Model
{
    use HasUuid;
    use HasFactory;

    public $incrementing = false;
    public $keyType = 'string';
    protected string $uuidColumnName = 'id';

    protected $fillable = [
        'quantity',
        'session_id',
        'product_id',
    ];

    /**
     * @param $query
     * @param $dateOne
     * @param $dateTwo
     * @return mixed
     */
    public function scopeCreatedAt($query, $dateOne, $dateTwo): mixed
    {
        return isset($dateOne) && isset($dateTwo)
            ? $query->whereBetween('created_at', [$dateOne, $dateTwo])
            : null;
    }
}
