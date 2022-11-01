<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class OrderDetail extends Model
{
    use HasUuid;
    use HasFactory;

    public $incrementing = false;
    public $keyType = 'string';
    protected string $uuidColumnName = 'id';

    protected $fillable = [
        'total',
        'user_id',
        'payment_id',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return HasOne
     */
    public function paymentDetails(): HasOne
    {
        return $this->hasOne(PaymentDetail::class, 'order_details_id');
    }

    /**
     * @return HasMany
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

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
