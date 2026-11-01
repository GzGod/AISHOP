<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>管理后台登录 — AIShop</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
<style>
:root{--bg:#0C0F1A;--surface:#111827;--border:#1E2D45;--text:#F1F5F9;--muted:#7C90AC;}
body{background:var(--bg);color:var(--text);font-family:system-ui,sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;}
.card{background:var(--surface);border:1px solid var(--border);border-radius:16px;}
input{background:rgba(255,255,255,.06);border:1px solid var(--border);color:var(--text);border-radius:10px;padding:.75rem 1rem;width:100%;font-family:inherit;font-size:.9rem;outline:none;transition:border-color .2s;}
input:focus{border-color:rgba(139,92,246,.6);}
.btn{background:linear-gradient(135deg,#7C3AED,#5B21B6);color:#fff;border:none;border-radius:10px;padding:.8rem 1.5rem;font-weight:600;font-size:.9rem;cursor:pointer;width:100%;transition:filter .2s;}
.btn:hover{filter:brightness(1.1);}
</style>
</head>
<body>
<div class="card p-8 w-full max-w-sm mx-4">
  <div class="flex items-center gap-2.5 mb-7">
    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
         style="background:linear-gradient(135deg,#7C3AED,#5B21B6);">
      <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" class="w-4 h-4"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </div>
    <span style="font-family:'Space Grotesk',sans-serif;font-weight:700;">AI<span style="color:#F59E0B;">Shop</span> 管理后台</span>
  </div>

  <?php if(isset($_GET['error'])): ?>
  <div class="mb-4 px-4 py-3 rounded-lg text-sm" style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:#FCA5A5;">
    用户名或密码错误
  </div>
  <?php endif; ?>

  <form method="POST" action="<?= url('admin/login') ?>" class="space-y-4">
    <div>
      <label class="text-sm font-medium mb-1.5 block" for="username">用户名</label>
      <input type="text" id="username" name="username" placeholder="admin" required autocomplete="username">
    </div>
    <div>
      <label class="text-sm font-medium mb-1.5 block" for="password">密码</label>
      <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
    </div>
    <button type="submit" class="btn mt-2">登录</button>
  </form>
</div>
</body>
</html>
<?php
echo ob_get_clean();
