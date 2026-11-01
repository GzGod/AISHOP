<?php
declare(strict_types=1);

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;

// ── Autoloader ──────────────────────────────────────────────────────────
require BASE_PATH . '/vendor/autoload.php';

// ── Environment ─────────────────────────────────────────────────────────
$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->safeLoad();

// ── Config helpers ───────────────────────────────────────────────────────
require BASE_PATH . '/bootstrap/helpers.php';

// ── Database (Eloquent via Capsule) ──────────────────────────────────────
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => env('DB_HOST', '127.0.0.1'),
    'port'      => env('DB_PORT', '3306'),
    'database'  => env('DB_DATABASE', 'aishop'),
    'username'  => env('DB_USERNAME', 'root'),
    'password'  => env('DB_PASSWORD', ''),
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => 'as_',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// ── Router ───────────────────────────────────────────────────────────────
require BASE_PATH . '/routes/web.php';
