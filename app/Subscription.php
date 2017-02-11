<?php

namespace App;

use App\Models\User;
use App\Models\Plan;
use Carbon\Carbon;
use Stripe\{Customer, Subscription as StripeSubscription};

class Subscription
{
    /**
     * The user associated with the subscription
     * @var User
     */
    protected $user;

    /**
     * An optional coupon for user's subscription
     * @var string
     */
    protected $coupon;

    /**
     * Create a new Subscription instance
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Create new subscription
     * @param  Plan   $plan
     * @param  string $token
     *
     * @return void
     */
    public function create(Plan $plan, string $token)
    {
        $customer = Customer::create([
            'email' => $this->user->email,
            'source' => $token,
            'plan' => $plan->name,
            'coupon' => $this->coupon,
        ]);

        $this->user->activate($customer);
    }

    public function usingCoupon($coupon)
    {
        if ($coupon) {
            $this->coupon = $coupon;
        }

        return $this;
    }

    public function retrieveStripeSubscription()
    {
        return StripeSubscription::retrieve($this->user->stripe_subscription);
    }

    /**
     * Get the Stripe customer object for the user
     * @return Customer
     */
    public function retrieveStripeCustomer()
    {
        return Customer::retrieve($this->user->stripe_id);
    }

    public function cancel($atPeriodEnd = true)
    {
        $customer = Customer::retrieve($this->user->stripe_id);

        $subscription = $customer->cancelSubscription([
            'at_period_end' => $atPeriodEnd,
        ]);

        $endDate = Carbon::createFromTimestamp($subscription->current_period_end);
        $this->user->deactivate($endDate);
    }
}
