<?php

if (($_GET['diag'] ?? '') === '1') {
    header('Content-Type: text/plain');
    try {
        require __DIR__ . '/../vendor/autoload.php';
        $app = require __DIR__ . '/../bootstrap/app.php';
        $app->make(Illuminate\Contracts\Http\Kernel::class)->bootstrap();

        echo "BOOTSTRAP OK\n";
        echo 'view bound: ' . ($app->bound('view') ? 'yes' : 'NO') . "\n";
        echo 'db bound: ' . ($app->bound('db') ? 'yes' : 'NO') . "\n";
        echo "Providers:\n" . implode("\n", array_keys($app->getLoadedProviders()));
    } catch (Throwable $e) {
        echo get_class($e) . ': ' . $e->getMessage() . "\n";
        echo $e->getFile() . ':' . $e->getLine() . "\n\n";
        echo implode("\n", array_slice(explode("\n", $e->getTraceAsString()), 0, 12));
    }
    exit;
}

require __DIR__ . '/../public/index.php';
