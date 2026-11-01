<?php
declare(strict_types=1);

// ── Session ──────────────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');
    ini_set('session.use_strict_mode', '1');
    session_name('AISHOP');
    session_start();
}

// ── Security headers ─────────────────────────────────────────────────────
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');

// ── Request parsing ──────────────────────────────────────────────────────
$method   = $_SERVER['REQUEST_METHOD'];
$uri      = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$uri      = '/' . trim((string)$uri, '/');

// ── Route table ──────────────────────────────────────────────────────────
// Each entry: [METHOD, regex, Controller class, action]
$routes = [
    // ── Shop front ───────────────────────────────────────────────────────
    ['GET',  '#^/$#',                                  \App\Controllers\Shop\IndexController::class,   'index'],
    ['GET',  '#^/category/([a-z]+)$#',                 \App\Controllers\Shop\IndexController::class,   'category'],
    ['GET',  '#^/product/(\d+)$#',                     \App\Controllers\Shop\ProductController::class, 'show'],
    ['GET',  '#^/order/([A-Z0-9]+)$#',                 \App\Controllers\Shop\OrderController::class,   'show'],

    // ── Public API ───────────────────────────────────────────────────────
    ['POST', '#^/api/order/create$#',                  \App\Controllers\Api\OrderController::class,    'create'],
    ['GET',  '#^/api/order/state$#',                   \App\Controllers\Api\OrderController::class,    'state'],
    ['POST', '#^/api/payment/callback/([a-z]+)$#',     \App\Controllers\Api\PaymentController::class,  'callback'],
    ['GET',  '#^/api/products$#',                      \App\Controllers\Api\ProductController::class,  'index'],

    // ── Admin ────────────────────────────────────────────────────────────
    ['GET',  '#^/admin/?$#',                           \App\Controllers\Admin\AuthController::class,   'loginForm'],
    ['POST', '#^/admin/login$#',                       \App\Controllers\Admin\AuthController::class,   'login'],
    ['GET',  '#^/admin/logout$#',                      \App\Controllers\Admin\AuthController::class,   'logout'],
    ['GET',  '#^/admin/dashboard$#',                   \App\Controllers\Admin\DashboardController::class, 'index'],
    ['GET',  '#^/admin/products$#',                    \App\Controllers\Admin\ProductController::class,'index'],
    ['GET',  '#^/admin/products/create$#',             \App\Controllers\Admin\ProductController::class,'create'],
    ['POST', '#^/admin/products$#',                    \App\Controllers\Admin\ProductController::class,'store'],
    ['GET',  '#^/admin/products/(\d+)/edit$#',         \App\Controllers\Admin\ProductController::class,'edit'],
    ['POST', '#^/admin/products/(\d+)$#',              \App\Controllers\Admin\ProductController::class,'update'],
    ['POST', '#^/admin/products/(\d+)/delete$#',       \App\Controllers\Admin\ProductController::class,'destroy'],
    ['GET',  '#^/admin/cards$#',                       \App\Controllers\Admin\CardController::class,   'index'],
    ['POST', '#^/admin/cards/import$#',                \App\Controllers\Admin\CardController::class,   'import'],
    ['GET',  '#^/admin/orders$#',                      \App\Controllers\Admin\OrderController::class,  'index'],
    ['POST', '#^/admin/orders/(\d+)/deliver$#',        \App\Controllers\Admin\OrderController::class,  'manualDeliver'],
    ['GET',  '#^/admin/payments$#',                    \App\Controllers\Admin\PaymentController::class,'index'],
    ['POST', '#^/admin/payments/(\d+)$#',              \App\Controllers\Admin\PaymentController::class,'update'],
];

// ── Dispatch ─────────────────────────────────────────────────────────────
$matched = false;
foreach ($routes as [$routeMethod, $pattern, $controllerClass, $action]) {
    if ($routeMethod !== $method && !($routeMethod === 'GET' && $method === 'HEAD')) {
        continue;
    }
    if (preg_match($pattern, $uri, $matches)) {
        $matched  = true;
        $params   = array_slice($matches, 1);
        $ctrl     = new $controllerClass();
        echo $ctrl->$action(...$params);
        break;
    }
}

if (!$matched) {
    http_response_code(404);
    echo view('errors.404', ['uri' => $uri]);
}
