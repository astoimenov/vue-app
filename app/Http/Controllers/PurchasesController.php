<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Stripe\{Charge, Customer};

class PurchasesController extends Controller
{
    public function store(Request $request)
    {
        $product = Product::findOrFail($request->product);
        $customer = Customer::create([
            'email' => $request->stripeEmail,
            'source' => $request->stripeToken,
        ]);

        Charge::create([
            'customer' => $customer->id,
            'amount' => $product->price,
            'currency' => 'usd',
        ]);

        return response()->json([
            'message' => 'Successful purchase!'
        ], Response::HTTP_OK);
    }
}
