<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class User extends Authenticatable
{
    use HasUuid;
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    public $incrementing = false;
    public $keyType = 'string';
    protected string $uuidColumnName = 'id';
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'telephone',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function userRole(): BelongsTo
    {
        return $this->belongsTo(UserRole::class, 'role_id');
    }

    /**
     * @return HasOne
     */
    public function userPayment(): HasOne
    {
        return $this->hasOne(UserPayment::class, 'user_id');
    }

    /**
     * @return HasOne
     */
    public function userAddress(): HasOne
    {
        return $this->hasOne(UserAddress::class, 'user_id');
    }

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeTypeId($query, $value): mixed
    {
        return isset($value) ? $query->where('role_id', '=', $value) : null;
    }

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeEmail($query, $value): mixed
    {
        return isset($value) ? $query->where('email', 'LIKE', '%' . $value . '%') : null;
    }

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeUsername($query, $value): mixed
    {
        return isset($value) ? $query->where('username', 'LIKE', '%' . $value . '%') : null;
    }
}
