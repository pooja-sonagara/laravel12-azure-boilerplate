<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use App\Models\User;
use App\Notifications\SignalRNotification;

class NotificationController extends Controller
{
    public function send(Request $request)
    {
        $message = $request->input('message', 'Hello from Laravel Notification!');

        // For demo: send to user #1
        $user = User::find(1);
        $user->notify(new SignalRNotification($message));


        return response()->json(['status' => true, 'message' => 'Notification queued']);
    }

    public function negotiate()
    {
        $connectionString = config('signalr.connection_string');
        $hub = config('signalr.hub');

        preg_match('/Endpoint=(.*?);/', $connectionString, $endpointMatch);
        preg_match('/AccessKey=(.*?);/', $connectionString, $accessKeyMatch);

        $endpoint = $endpointMatch[1] ?? '';
        $accessKey = $accessKeyMatch[1] ?? '';

        // ✅ Ensure correct format: https://xxx.service.signalr.net/client/?hub=notifications
        $audience = rtrim($endpoint, '/') . "/client/?hub=$hub";
        $exp = time() + 3600;

        $token = \Firebase\JWT\JWT::encode([
            'aud' => $audience,
            'exp' => $exp,
        ], $accessKey, 'HS256');

        return response()->json([
            'url' => $audience,
            'accessToken' => $token,
        ]);
    }


    private function generateJwt($audience, $accessKey)
    {
        $exp = time() + 3600;
        return JWT::encode([
            'aud' => $audience,
            'exp' => $exp,
        ], $accessKey, 'HS256');
    }
}
