<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class SubMenu extends Model
{
    use HasUuid;
    use HasFactory;

    public $incrementing = false;
    public $keyType = 'string';
    public $table = 'sub_menu';
    protected string $uuidColumnName = 'id';

    protected $fillable = [
        'title',
        'url',
        'menu_id',
    ];
}
