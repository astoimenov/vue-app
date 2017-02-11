<?php

namespace App\Billing;

use App\Subscription;
use Carbon\Carbon;
use Stripe\Customer;

trait Billable
{
    public function activate(Customer $customer, $reactivate = false)
    {
        return $this->update([
            'stripe_active' => true,
            'stripe_id' => $reactivate ? $this->stripe_id : $customer->id,
            'stripe_subscription' => $customer->subscriptions->data[0]->id,
            'subscription_ends_at' => null,
        ]);
    }

    public function deactivate($endDate = null)
    {
        $endDate = $endDate ?? Carbon::now();

        return $this->update([
            'stripe_active' => false,
            'subscription_ends_at' => $endDate,
        ]);
    }

    public function isSubscribed()
    {
        return $this->stripe_active;
    }

    public function isActive()
    {
        return is_null($this->subscription_ends_at) || $this->isOnGracePeriod();
    }

    public function isOnGracePeriod()
    {
        if (!$this->subscription_ends_at) {
            return false;
        }

        return Carbon::now()->lt($this->subscription_ends_at);
    }

    /**
     * Create new subscription for user.
     *
     * @return Subscription
     */
    public function subscription()
    {
        return new Subscription($this);
    }

    public static function byStripeId($stripeId)
    {
        return self::where('stripe_id', $stripeId)->firstOrFail();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
