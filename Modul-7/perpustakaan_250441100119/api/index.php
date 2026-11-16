<?php

$storagePath = '/tmp/laravel-storage';

$directories = [
    'framework/cache/data',
    'framework/sessions',
    'framework/views',
    'logs',
];

foreach ($directories as $directory) {
    $path = $storagePath . '/' . $directory;

    if (!is_dir($path)) {
        mkdir($path, 0775, true);
    }
}

$_ENV['LARAVEL_STORAGE_PATH'] = $storagePath;
$_SERVER['LARAVEL_STORAGE_PATH'] = $storagePath;

$_ENV['VIEW_COMPILED_PATH'] = $storagePath . '/framework/views';
$_SERVER['VIEW_COMPILED_PATH'] = $storagePath . '/framework/views';

require __DIR__ . '/../public/index.php';