<?php

use App\Models\User;
use App\Subscription;
use App\Models\Plan;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubscriptionTest extends TestCase
{
    use DatabaseTransactions;
    use InteractsWithStripe;

    /** @test */
    public function it_subscribes_a_user()
    {
        // Given: I have a registered user hwo is not subscribed
        // When: I create a subscription for that user
        $user = $this->makeSubscribedUser([
            'stripe_active' => false,
        ]);

        // Then: They should have recurring subscription with Stripe and should be active in our system
        $this->assertTrue($user->isSubscribed());

        try {
            $user->subscription()->retrieveStripeSubscription();
        } catch (Exception $e) {
            $this->fail('Expected subscription');
        }
    }

    /** @test */
    public function it_cancels_a_users_subscription()
    {
        // Given: we have user's subscription
        $user = $this->makeSubscribedUser();

        // When: we cancel their subscription
        $user->subscription()->cancel();

        // Then: the subscription should be canceled on Stripe's end ..
        $stripeSubscription = $user->subscription()->retrieveStripeSubscription();
        $this->assertNotNull($stripeSubscription->canceled_at);

        // .. and in our system
        $this->assertFalse($user->isSubscribed());
        $this->assertNotNull($user->subscription_ends_at);
    }

    /** @test */
    public function it_subscribes_a_user_using_a_coupon_code()
    {
        // Given: I have a registered user hwo is not subscribed
        // When: Create a subscription for that user with coupon code
        $user = factory(User::class)->create([
            'stripe_active' => false,
        ]);

        $testCoupon = 'TEST-COUPON';
        $user->subscription()
            ->usingCoupon($testCoupon)
            ->create(new Plan([
                'name' => 'monthly',
            ]), $this->getStripeToken());

        // Then: Fetch the user's Stripe customer object
        $customer = $user->subscription()->retrieveStripeCustomer();
        try {
            $appliedCoupon = $customer->invoices()->data[0]->discount->coupon->id;

            // Make sure that their last invoice includes the coupon discount
            $this->assertEquals($appliedCoupon, $testCoupon);
        } catch (Exception $e) {
            $this->fail('Expected coupon applied to the Stripe customer, but did not find one');
        }

    }

    protected function makeSubscribedUser($attributes = [])
    {
        $user = factory(User::class)->create($attributes);

        $user->subscription()->create(new Plan([
            'name' => 'monthly',
        ]), $this->getStripeToken());

        return $user;
    }
}
