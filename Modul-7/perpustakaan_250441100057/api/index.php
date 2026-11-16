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

if (($_GET['diag'] ?? '') === '1') {
    header('Content-Type: text/plain');
    try {
        require __DIR__ . '/../vendor/autoload.php';
        $app = require __DIR__ . '/../bootstrap/app.php';
        $app->make(Illuminate\Contracts\Http\Kernel::class)->bootstrap();

        echo "BOOTSTRAP OK\n";
        echo 'view bound: ' . ($app->bound('view') ? 'yes' : 'NO') . "\n";
        echo 'db bound: ' . ($app->bound('db') ? 'yes' : 'NO') . "\n";
    } catch (Throwable $e) {
        echo get_class($e) . ': ' . $e->getMessage() . "\n";
        echo $e->getFile() . ':' . $e->getLine() . "\n\n";
        echo implode("\n", array_slice(explode("\n", $e->getTraceAsString()), 0, 12));
    }
    exit;
}

require __DIR__ . '/../public/index.php';