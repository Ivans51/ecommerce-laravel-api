<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class OrderItem extends Model
{
    use HasUuid;
    use HasFactory;

    public $incrementing = false;
    public $keyType = 'string';
    protected string $uuidColumnName = 'id';

    protected $fillable = [
        'quantity',
        'order_id',
        'product_id',
    ];

    /**
     * @return BelongsTo
     */
    public function products(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
