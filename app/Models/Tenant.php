<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasUuid;
use Database\Factories\TenantFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $quantity
 */
class Tenant extends Model
{
    use HasFactory;
    use HasUuid;

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
        'quantity',
        'stripe_id',
        'stripe_plan',
    ];

    /**
     * @return array<string>
     */
    protected function casts(): array
    {
        return [
            'trial_ends_at' => 'datetime',
            'ends_at' => 'datetime',
            'canceled_at' => 'datetime',
            'trial_expiring_mail_sent_at' => 'datetime',
            'trial_expired_mail_sent_at' => 'datetime',
        ];
    }

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
        if (! $this->trial_ends_at) {
            return false;
        }

        $trialEndsAt = Carbon::parse($this->trial_ends_at);

        return $trialEndsAt->isFuture() || $trialEndsAt->isToday();
    }

    public function scopeOnTrial(Builder $query): Builder
    {
        return $query->whereNotNull('trial_ends_at')->where('trial_ends_at', '>=', now()->toDateTimeString());
    }

    public function scopeTrialExpiredToday(Builder $query): Builder
    {
        return $query->whereNotNull('trial_ends_at')->where('trial_ends_at', now()->toDateTimeString());
    }

    public function isOnGracePeriod(): bool
    {
        if ($this->stripe_status !== 'cancelled') {
            return false;
        }

        if ($this->ends_at === null) {
            return false;
        }

        $endsAt = Carbon::parse($this->ends_at);

        return $endsAt->isFuture();
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
        return match ($this->stripe_status) {
            'active', 'lifetime' => true,
            'cancelled' => $this->isOnGracePeriod(),
            default => false,
        };
    }

    public function trailEndsInDays(int $days = 3): bool
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

    public function wasAlreadySentTrialExpiredMail(): bool
    {
        return ! ($this->trial_ended_mail_sent_at === null);
    }

    public function rememberHasBeenSentTrialEndingSoonMail(): void
    {
        $this->trial_ending_mail_sent_at = now()->toDateTimeString();
        $this->save();
    }

    public function rememberHasBeenSentTrialEndedMail(): void
    {
        $this->stripe_status = 'Trial Ended';
        $this->trial_ended_mail_sent_at = now()->toDateTimeString();
        $this->save();
    }

    public function setTrialEndedStatus(): void
    {
        $this->stripe_status = 'Trial Ended';
        $this->save();
    }

    public function setStripeId(string $id): void
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
