<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class Menu extends Model
{
    use HasUuid;
    use HasFactory;

    public $incrementing = false;
    public $keyType = 'string';
    protected string $uuidColumnName = 'id';

    protected $fillable = [
        'title',
        'url',
        'type_id',
    ];

    /**
     * @return HasMany
     */
    public function subMenu(): HasMany
    {
        return $this->hasMany(SubMenu::class, 'menu_id');
    }
}
