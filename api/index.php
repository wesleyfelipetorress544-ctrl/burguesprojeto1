<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$storagePath = sys_get_temp_dir().DIRECTORY_SEPARATOR.'lanchoneteburgues';
$app->useStoragePath($storagePath);

foreach ([
    'app/private',
    'app/public',
    'framework/cache/data',
    'framework/cache/locks',
    'framework/sessions',
    'framework/views',
    'logs',
] as $directory) {
    $path = $app->storagePath($directory);

    if (! is_dir($path) && ! mkdir($path, 0755, true) && ! is_dir($path)) {
        throw new RuntimeException("Unable to create Laravel storage directory: {$path}");
    }
}

if (file_exists($maintenance = $app->storagePath('framework/maintenance.php'))) {
    require $maintenance;
}

$app->handleRequest(Request::capture());
