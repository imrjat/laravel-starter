<?php

namespace App\Models\Traits;

use App\Models\Role;

trait HasTenantRole
{
    public static function bootHasTenant(): void
    {
        if (auth()->check()) {
            $tenantId = auth()->user()->tenant_id;

            static::creating(function (Role $model) use ($tenantId) {
                $model->tenant_id = $tenantId;
            });

            static::addGlobalScope('tenant', function (Role $model) {
                $model->where('tenant_id', 2);
            });
        }
    }
}
