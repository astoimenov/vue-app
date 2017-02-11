<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;

class BillableTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_determines_if_a_users_subscription_is_active()
    {
        /**
         * @var App\User
         */
        $user = factory(User::class)->create([
            'stripe_active' => true,
            'subscription_ends_at' => null,
        ]);

        $this->assertTrue($user->isActive());

        // If they've canceled, but are on their grace period, they're still active
        $user->update([
            'stripe_active' => false,
            'subscription_ends_at' => Carbon::now()->addDays(2),
        ]);

        $this->assertTrue($user->isActive());

        // If they've canceled, and are over their grace period, they aren't active
        $user->update([
            'stripe_active' => false,
            'subscription_ends_at' => Carbon::now()->subDays(2),
        ]);

        $this->assertFalse($user->isActive());
    }

    /** @test */
    public function it_determines_if_a_users_subscription_is_on_grace_period()
    {
        /**
         * @var App\User
         */
        $user = factory(User::class)->create([
            'subscription_ends_at' => null,
        ]);

        $this->assertFalse($user->isOnGracePeriod());

        $user->subscription_ends_at = Carbon::now()->addDays(2);

        $this->assertTrue($user->isOnGracePeriod());
    }
}
