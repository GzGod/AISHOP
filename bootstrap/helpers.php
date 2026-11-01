<?php
declare(strict_types=1);

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed {
        $value = $_ENV[$key] ?? getenv($key);
        if ($value === false) return $default;
        return match(strtolower((string)$value)) {
            'true', '(true)'   => true,
            'false', '(false)' => false,
            'null', '(null)'   => null,
            'empty', '(empty)' => '',
            default            => $value,
        };
    }
}

if (!function_exists('base_path')) {
    function base_path(string $path = ''): string {
        return BASE_PATH . ($path ? DIRECTORY_SEPARATOR . ltrim($path, '/') : '');
    }
}

if (!function_exists('view')) {
    function view(string $template, array $data = []): string {
        $file = base_path('resources/views/' . str_replace('.', '/', $template) . '.php');
        if (!file_exists($file)) {
            http_response_code(500);
            return "<h1>View not found: {$template}</h1>";
        }
        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        return ob_get_clean();
    }
}

if (!function_exists('json')) {
    function json(mixed $data, int $status = 200): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url): void {
        header('Location: ' . $url);
        exit;
    }
}

if (!function_exists('input')) {
    function input(string $key, mixed $default = null): mixed {
        $val = $_POST[$key] ?? $_GET[$key] ?? $default;
        if (is_string($val)) {
            return htmlspecialchars(trim($val), ENT_QUOTES, 'UTF-8');
        }
        return $val;
    }
}

if (!function_exists('abort')) {
    function abort(int $code, string $message = ''): void {
        http_response_code($code);
        echo view('errors.error', ['code' => $code, 'message' => $message ?: "HTTP {$code}"]);
        exit;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_check')) {
    function csrf_check(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $token = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            abort(419, 'CSRF token mismatch');
        }
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string {
        $base = rtrim(env('APP_URL', ''), '/');
        return $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string {
        return url('assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('format_price')) {
    function format_price(float|int $amount): string {
        return '¥ ' . number_format((float)$amount, 2);
    }
}

if (!function_exists('generate_trade_no')) {
    function generate_trade_no(): string {
        return 'AS' . date('YmdHis') . strtoupper(substr(uniqid('', true), -8));
    }
}
