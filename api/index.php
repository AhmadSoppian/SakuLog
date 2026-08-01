<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Vercel's filesystem is read-only except /tmp, so redirect Laravel's
// storage (logs, compiled views, framework cache) to the ephemeral /tmp.
$storagePath = '/tmp/storage';
foreach (['framework/views', 'framework/cache/data', 'framework/sessions', 'logs'] as $directory) {
    @mkdir($storagePath.'/'.$directory, 0777, true);
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->useStoragePath($storagePath);

$app->handleRequest(Request::capture());
