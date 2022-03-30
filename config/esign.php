<?php

declare(strict_types=1);

return [
    'appId' => env('ESIGN_APPID', 'your-app-id') ?: getenv('ESIGN_APPID'),                            // APP ID
    'secret' => env('ESIGN_SECRET', 'your-app-secret') ?: getenv('ESIGN_SECRET'),                          // APP SECRET
    'server' => env('ESIGN_SERVER', 'https://smlopenapi.esign.cn') ?: getenv('ESIGN_SERVER'),           // esign api v2 url.
    'notify_url' => env('ESIGN_NOTIFY_URL', 'XXXXXX/api/esign/callback') ?: getenv('ESIGN_NOTIFY_URL'),         // callback url
];
