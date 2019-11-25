<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Agent
    |--------------------------------------------------------------------------
    |
    | Every API request should have a User-Agent header with your app name.
    | If you don’t provide it, RAWG may ban your requests.
    |
     */

    'user_agent' => 'ADD_YOUR_USER_AGENT_HERE',

    /*
    |--------------------------------------------------------------------------
    | Verify SSL Peer
    |--------------------------------------------------------------------------
    |
    | Will be used for all web services to verify
    | SSL peer (SSL certificate validation).
    |
     */
    'ssl_verify_peer' => false,

    /*
    |--------------------------------------------------------------------------
    | API URL
    |--------------------------------------------------------------------------
    |
    | Base URL for all RAWG endpoints.
    |
     */

    'api_url' => 'https://api.rawg.io/api/',
];
