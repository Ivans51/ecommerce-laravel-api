<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class UserAddress extends Model
{
    use HasUuid;
    use HasFactory;

    public $incrementing = false;
    public $keyType = 'string';
    protected string $uuidColumnName = 'id';

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'address_line1',
        'address_line2',
        'city',
        'postal_code',
        'country',
        'telephone',
        'mobile',
        'user_id',
    ];
}
