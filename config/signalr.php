<?php

return [
    'connection_string' => env('AZURE_SIGNALR_CONNECTION_STRING'),
    'hub' => env('AZURE_SIGNALR_HUB_NAME', 'notifications'),
];
