<?php

declare(strict_types=1);

return [
    'appId' => env('ESIGN_APPID', 'your-app-id'),                            // APP ID
    'secret' => env('ESIGN_SECRET', 'your-app-secret'),                          // APP SECRET
    'server' => env('ESIGN_SERVER', 'https://smlopenapi.esign.cn'),           // esign api v2 url.
    'notify_url' => env('ESIGN_NOTIFY_URL', 'XXXXXX/api/esign/callback'),         // callback url
];
