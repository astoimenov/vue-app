<?php

use Stripe\Token;

trait InteractsWithStripe
{
    protected function getStripeToken()
    {
        return Token::create([
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => 1,
                'exp_year' => 2019,
                'cvc' => 123,
            ],
        ])->id;
    }
}
