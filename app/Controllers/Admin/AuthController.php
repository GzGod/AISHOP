<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Admin;

class AuthController
{
    private function requireAdmin(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['admin_id'])) {
            redirect(url('admin'));
        }
    }

    public function loginForm(): string
    {
        if (!empty($_SESSION['admin_id'])) {
            redirect(url('admin/dashboard'));
        }
        return view('admin.login', []);
    }

    public function login(): never
    {
        $user = input('username', '');
        $pass = input('password', '');

        $admin = Admin::where('username', $user)->first();
        if ($admin && password_verify($pass, $admin->password)) {
            $_SESSION['admin_id']   = $admin->id;
            $_SESSION['admin_user'] = $admin->username;
            $admin->last_login_at = date('Y-m-d H:i:s');
            $admin->last_login_ip = $_SERVER['REMOTE_ADDR'] ?? null;
            $admin->save();
            redirect(url('admin/dashboard'));
        }
        redirect(url('admin?error=1'));
    }

    public function logout(): never
    {
        session_destroy();
        redirect(url('admin'));
    }
}
