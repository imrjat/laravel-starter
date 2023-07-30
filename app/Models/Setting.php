<?php

namespace App\Models;

use App\Models\Traits\HasTenant;
use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasUuid;
    use HasTenant;

    protected $fillable = [
        'tenant_id',
        'key',
        'value',
    ];
}
