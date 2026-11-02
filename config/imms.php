<?php

/*
|--------------------------------------------------------------------------
| IMMS platform connection
|--------------------------------------------------------------------------
|
| This app is a thin client. It owns no user, role or permission tables — it
| authenticates against the IMMS API and renders what comes back. Point
| `api_url` at the API domain served by the main application.
|
*/

return [

    'api_url' => env('IMMS_API_URL', 'https://api.imjpj.my'),

    /*
    | The API binds its routes to the `api_url` host, so a local API still needs
    | the host to resolve. Set IMMS_API_URL=http://api.imjpj.my and add a hosts
    | entry pointing at 127.0.0.1 when developing against a local server.
    */

    'timeout' => (int) env('IMMS_API_TIMEOUT', 15),

];
