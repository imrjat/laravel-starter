<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Database\Factories\TenantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tenant extends Model
{
    use HasUuid;
    use HasFactory;

    protected $fillable = [
        'name',
        'owner_id',
        'trial_ends_at',
        'ends_at',
        'canceled_at',
        'cancel_at_period_end',
        'trial_expiring_mail_sent_at',
        'trial_expired_mail_sent_at',
        'extra_billing_information'
    ];

    protected static function newFactory(): TenantFactory
    {
        return TenantFactory::new();
    }

    public function owner(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'owner_id');
    }
}
