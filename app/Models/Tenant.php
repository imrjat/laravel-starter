<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Database\Factories\TenantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

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
        'extra_billing_information',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'ends_at' => 'datetime',
        'canceled_at' => 'datetime',
        'trial_expiring_mail_sent_at' => 'datetime',
        'trial_expired_mail_sent_at' => 'datetime',
    ];

    protected static function newFactory(): TenantFactory
    {
        return TenantFactory::new();
    }

    public function owner(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'owner_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'tenant_id', 'id');
    }

    public function isOnTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    //write scope is on trial
    public function scopeOnTrial($query)
    {
        return $query->whereNotNull('trial_ends_at')->where('trial_ends_at', '>', now());
    }

    public function isOnGracePeriod(): bool
    {
        return $this->stripe_status === 'cancelled' && $this->ends_at->isFuture();
    }

    public function isOnLifetime(): bool
    {
        return $this->stripe_status === 'lifetime';
    }

    public function isValid(): bool
    {
        return $this->isActive() || $this->isOnTrial() || $this->isOnGracePeriod() || $this->isOnLifetime();
    }

    public function isActive(): bool
    {
        return $this->stripe_status === 'active' || $this->isOnGracePeriod() || $this->isOnLifetime();
    }

    public function trailEndsInDays($days = 3): bool
    {
        if (! $this->isOnTrial()) {
            return false;
        }

        $trialEndsAtDate = Carbon::parse($this->trial_ends_at)->toDateString();
        $targetDate = Carbon::now()->addDays($days)->toDateString();

        return $trialEndsAtDate === $targetDate;
    }

    public function wasAlreadySentTrialEndingSoonMail(): bool
    {
        return ! ($this->trial_ending_mail_sent_at === null);
    }

    public function wasAlreadySentTrialEndedMail(): bool
    {
        return ! ($this->trial_ended_mail_sent_at === null);
    }

    public function rememberHasBeenSentTrialEndingSoonMail(): void
    {
        $this->trial_ending_mail_sent_at = now();
        $this->save();
    }

    public function setTrialEndedStatus(): void
    {
        $this->stripe_status = 'Trial Ended';
        $this->save();
    }

    public function setStripeId($id): void
    {
        $this->stripe_id = $id;
        $this->save();
    }

    public function removeStripeId(): void
    {
        $this->stripe_id = null;
        $this->save();
    }
}
