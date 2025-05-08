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
        if ($request->isMethod('get')) {
            $verify_token = 'olistoreapptoken0408';

            $mode = $request->query('hub_mode');
            $token = $request->query('hub_verify_token');
            $challenge = $request->query('hub_challenge');

            if ($mode === 'subscribe' && $token === $verify_token) {
                return response($challenge, 200);
            } else {
                return response('Forbidden', 403);
            }
        }
        // Procesamiento de eventos entrantes (POST)
        if ($request->isMethod('post')) {
            // Guarda el evento para debug (opcional)
            Log::info('Evento de WhatsApp recibido:', $request->all());

            // Aquí puedes procesar los datos entrantes según lo que envíe WhatsApp

            return response('EVENT_RECEIVED', 200);
        }

        return response('Method Not Allowed', 405);
    }
}
