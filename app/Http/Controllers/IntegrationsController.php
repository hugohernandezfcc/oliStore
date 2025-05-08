<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IntegrationsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function inboudEvent(Request $request)
    {
        return response()->json(
            [
                'status' => 'success :D',
                'message' => 'Webhook received successfully',
                'data' => $request->all(),
            ],
            200
        );
    }
}
