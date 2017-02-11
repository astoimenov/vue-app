<?php

use App\Http\Controllers\WebHooksController;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WebhooksControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_converts_a_stripe_event_name_to_a_method_name()
    {
        /*
         * Convert: customer.subscription.deleted
         * Into: whenCustomerSubscriptionDeleted
         */
        $name = (new WebHooksController())->eventToMethod('customer.subscription.deleted');

        $this->assertEquals('whenCustomerSubscriptionDeleted', $name);
    }

    /** @test */
    public function it_deactivates_a_users_subscription_if_deleted_on_stripes_end()
    {
        // Given: I have a user that is marked as subscriber
        $user = factory(User::class)->create([
            'stripe_active' => true,
            'stripe_id' => 'fake_stripe_id',
        ]);

        // When: I trigger the WebHooksController with the customer.subscription.deleted event
        $this->post('stripe/webhook', [
            'type' => 'customer.subscription.deleted',
            'data' => [
                'object' => [
                    'customer' => $user->stripe_id,
                ],
            ],
        ]);

        // Then: The user's subscription should be deactivated
        $this->assertFalse($user->fresh()->isSubscribed());
    }
}
