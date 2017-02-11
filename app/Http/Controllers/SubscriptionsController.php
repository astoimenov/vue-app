<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationForm;
use App\Models\Plan;
use Illuminate\Http\Response;

class SubscriptionsController extends Controller
{
    public function index()
    {
        return view('plans.index', [
            'plans' => Plan::all(),
        ]);
    }

    public function store(RegistrationForm $form)
    {
        try {
            $form->save();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'message' => 'Successful subscription!',
        ], Response::HTTP_OK);
    }
}
