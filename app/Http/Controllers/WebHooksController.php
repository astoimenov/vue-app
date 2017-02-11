<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;

class WebHooksController extends Controller
{
    public function handle(Request $request)
    {
        $method = $this->eventToMethod($request->type);
        if (method_exists($this, $method)) {
            $response = $this->{$method}($request);

            return response('Webhook activated');
        }
    }

    protected function whenCustomerSubscriptionDeleted(Request $request)
    {
        $stripeId = $request->input('data.object.customer');
        $user = User::byStripeId($stripeId);
        $user->deactivate();

        return [
            'content' => 'Subscription deactivated!',
            'status' => Response::HTTP_OK,
        ];
    }

    protected function whenChargeSucceeded(Request $request)
    {
        $stripeId = $request->input('data.object.customer');
        $user = User::byStripeId($stripeId);
        $user->payments()->create([
            'amount' => $request->input('data.object.amount'),
            'charge_id' => $request->input('data.object.id'),
        ]);
    }

    public function eventToMethod($event)
    {
        return 'when'.studly_case(str_replace('.', '_', $event));
    }
}
