<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Firebase\JWT\JWT;

class SignalRChannel
{
    public function send($notifiable, Notification $notification)
    {
        // Get data from Notification
        $data = $notification->toSignalR($notifiable);

        $connectionString = config('signalr.connection_string');
        $hub = config('signalr.hub');

        preg_match('/Endpoint=(.*?);/', $connectionString, $endpointMatch);
        preg_match('/AccessKey=(.*?);/', $connectionString, $accessKeyMatch);

        $endpoint = $endpointMatch[1] ?? '';
        $accessKey = $accessKeyMatch[1] ?? '';

        $url = rtrim($endpoint, '/') . "/api/v1/hubs/$hub";

        // Generate JWT token for SignalR REST API
        $token = JWT::encode([
            'aud' => $url,
            'exp' => time() + 3600
        ], $accessKey, 'HS256');

        // Send to SignalR hub
        Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $token"
        ])->post($url, $data);
    }
}
