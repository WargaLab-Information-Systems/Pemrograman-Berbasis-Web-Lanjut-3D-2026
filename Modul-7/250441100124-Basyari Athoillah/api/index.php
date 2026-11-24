<?php

/*
|--------------------------------------------------------------------------
| Vercel Serverless Entrypoint
|--------------------------------------------------------------------------
|
| Vercel requires this file to live inside the "api" directory, and it also
| runs it as the router of the PHP built-in server. That makes PHP report
| SCRIPT_NAME as "/api/index.php", so Laravel treats "/api" as the
| application's base URL and strips it from every request path
| ("/api/buku" arrives as "/buku" and returns HTML instead of JSON).
|
| Pointing the script name at the document root keeps the request path
| intact, so Laravel receives the original URI.
|
*/

$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__.'/../public/index.php';

require __DIR__.'/../public/index.php';
