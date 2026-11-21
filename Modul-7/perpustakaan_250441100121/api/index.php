<?php

/**
 * Pintu masuk (entry point) aplikasi Laravel di Vercel.
 *
 * Vercel menjalankan PHP sebagai serverless function, sehingga semua request
 * diarahkan ke berkas ini oleh vercel.json. Berkas ini menggantikan peran
 * public/index.php yang dipakai saat aplikasi berjalan di lokal.
 */

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Vercel's filesystem is read-only except for /tmp. Every directory Laravel
// writes to (bootstrap caches, compiled views, logs, storage) must live there.
$tmp = '/tmp';
$storagePath = $tmp.'/storage';
$bootstrapCache = $tmp.'/bootstrap-cache';
$viewsPath = $tmp.'/views';

$writableDirs = [
    $bootstrapCache,
    $viewsPath,
    $storagePath.'/app/private',
    $storagePath.'/app/public',
    $storagePath.'/framework/cache/data',
    $storagePath.'/framework/sessions',
    $storagePath.'/framework/views',
    $storagePath.'/framework/testing',
    $storagePath.'/logs',
];

foreach ($writableDirs as $dir) {
    if (! is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

$runtimeEnv = [
    'APP_PACKAGES_CACHE' => $bootstrapCache.'/packages.php',
    'APP_SERVICES_CACHE' => $bootstrapCache.'/services.php',
    'APP_CONFIG_CACHE' => $bootstrapCache.'/config.php',
    'APP_ROUTES_CACHE' => $bootstrapCache.'/routes.php',
    'VIEW_COMPILED_PATH' => $viewsPath,
    'LOG_CHANNEL' => 'stderr',
];

foreach ($runtimeEnv as $key => $value) {
    putenv("{$key}={$value}");
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $storagePath.'/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// storage_path() is used by logs, cache, sessions and compiled views, so it has
// to point at the only writable location too.
$app->useStoragePath($storagePath);

$app->handleRequest(Request::capture());
