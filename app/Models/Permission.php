<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasUuid;

    protected $fillable = [
        'name',
        'label',
        'module',
        'guard_name',
    ];

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
    ];
}
