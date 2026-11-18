<?php

$storage = '/tmp/storage';

foreach ([
    'framework/views',
    'framework/cache/data',
    'framework/sessions',
    'logs',
] as $dir) {
    if (! is_dir("$storage/$dir")) {
        mkdir("$storage/$dir", 0755, true);
    }
}

$env = [
    'APP_STORAGE_PATH' => $storage,
    'VIEW_COMPILED_PATH' => "$storage/framework/views",
    'APP_PACKAGES_CACHE' => '/tmp/packages.php',
    'APP_SERVICES_CACHE' => '/tmp/services.php',
    'APP_CONFIG_CACHE' => '/tmp/config.php',
    'APP_ROUTES_CACHE' => '/tmp/routes.php',
    'APP_EVENTS_CACHE' => '/tmp/events.php',
];

foreach ($env as $key => $value) {
    putenv("$key=$value");
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}

if (isset($_GET['_vercel_path'])) {
    $path = $_GET['_vercel_path'];

    unset($_GET['_vercel_path'], $_REQUEST['_vercel_path']);

    $query = http_build_query($_GET);

    $_SERVER['REQUEST_URI'] = $path . ($query ? '?' . $query : '');
    $_SERVER['QUERY_STRING'] = $query;
}

$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';

require __DIR__ . '/../public/index.php';