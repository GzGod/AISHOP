<?php
/** Admin layout wrapper — wraps $content with a sidebar nav */
$adminUser = $_SESSION['admin_user'] ?? 'admin';
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
function adminNavItem(string $path, string $icon, string $label, string $current): string {
    $active = str_starts_with($current, $path);
    $bg = $active ? 'background:rgba(139,92,246,.2);color:#fff;border:1px solid rgba(139,92,246,.35);' : 'color:#7C90AC;border:1px solid transparent;';
    return "<a href=\"".url(ltrim($path,'/')).'"'
         . " class=\"flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 cursor-pointer\""
         . " style=\"$bg\">"
         . "<svg viewBox=\"0 0 20 20\" fill=\"currentColor\" class=\"w-4 h-4\">$icon</svg>"
         . htmlspecialchars($label)
         . "</a>";
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($title ?? '管理后台') ?> — AIShop</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--bg:#080B14;--surface:#111827;--raised:#162032;--border:#1E2D45;--border-s:#172135;--text:#F1F5F9;--muted:#7C90AC;--muted2:#4E637E;--accent:#F59E0B;--cta:#6D28D9;--cta2:#8B5CF6;--cta3:#A78BFA;--ok:#10B981;--danger:#EF4444;}
*{box-sizing:border-box;margin:0;padding:0;}
html,body{background:var(--bg);color:var(--text);font-family:'DM Sans',system-ui,sans-serif;font-size:15px;}
h1,h2,h3,.display{font-family:'Space Grotesk',sans-serif;}
a{text-decoration:none;color:inherit;}
.card{background:var(--surface);border:1px solid var(--border-s);border-radius:12px;}
.btn-cta{background:linear-gradient(135deg,#7C3AED,#5B21B6);color:#fff;border:none;border-radius:8px;padding:.55rem 1.1rem;font-size:.85rem;font-weight:600;cursor:pointer;transition:filter .15s;}
.btn-cta:hover{filter:brightness(1.1);}
.btn-ghost{background:transparent;border:1px solid var(--border);color:var(--text);border-radius:8px;padding:.5rem 1rem;font-size:.85rem;cursor:pointer;transition:border-color .15s;}
.btn-ghost:hover{border-color:var(--cta3);}
.btn-danger{background:rgba(239,68,68,.15);border:1px solid rgba(239,68,68,.3);color:#FCA5A5;border-radius:8px;padding:.5rem 1rem;font-size:.85rem;cursor:pointer;}
.btn-danger:hover{background:rgba(239,68,68,.25);}
input,select,textarea{background:rgba(255,255,255,.06);border:1px solid var(--border);color:var(--text);border-radius:8px;padding:.6rem .9rem;font-family:inherit;font-size:.88rem;outline:none;transition:border-color .2s;}
input:focus,select:focus,textarea:focus{border-color:rgba(139,92,246,.6);}
table{width:100%;border-collapse:collapse;}
th{font-weight:600;font-size:.78rem;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);padding:.6rem 1rem;text-align:left;border-bottom:1px solid var(--border-s);}
td{padding:.7rem 1rem;border-bottom:1px solid rgba(30,45,69,.5);font-size:.88rem;vertical-align:middle;}
tr:last-child td{border-bottom:none;}
tr:hover td{background:rgba(255,255,255,.025);}
.badge{display:inline-block;padding:.15rem .6rem;border-radius:9999px;font-size:.73rem;font-weight:600;}
.badge-ok{background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.3);color:#34D399;}
.badge-warn{background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);color:#FCD34D;}
.badge-danger{background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);color:#FCA5A5;}
.badge-muted{background:rgba(255,255,255,.05);border:1px solid var(--border-s);color:var(--muted);}
::-webkit-scrollbar{width:6px;height:6px;} ::-webkit-scrollbar-track{background:var(--bg);} ::-webkit-scrollbar-thumb{background:#1E2D45;border-radius:4px;}
</style>
</head>
<body class="flex h-screen overflow-hidden">

<!-- Sidebar -->
<aside class="w-56 flex-shrink-0 flex flex-col" style="background:rgba(8,11,20,.98);border-right:1px solid var(--border-s);">
  <div class="px-4 py-5 flex items-center gap-2.5">
    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
         style="background:linear-gradient(135deg,#7C3AED,#5B21B6);">
      <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" class="w-3.5 h-3.5"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </div>
    <span class="display font-bold text-sm">AI<span style="color:#F59E0B;">Shop</span></span>
  </div>
  <nav class="flex-1 px-3 space-y-1 overflow-y-auto py-2">
    <?= adminNavItem('/admin/dashboard','<path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/>','仪表盘',$currentPath) ?>
    <?= adminNavItem('/admin/products','<path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>','商品管理',$currentPath) ?>
    <?= adminNavItem('/admin/cards','<path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>','卡密管理',$currentPath) ?>
    <?= adminNavItem('/admin/orders','<path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>','订单管理',$currentPath) ?>
    <?= adminNavItem('/admin/payments','<path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>','支付渠道',$currentPath) ?>
  </nav>
  <div class="px-3 py-4 border-t" style="border-color:var(--border-s);">
    <div class="text-xs mb-2" style="color:var(--muted);">登录用户：<?= htmlspecialchars($adminUser) ?></div>
    <a href="<?= url('admin/logout') ?>" class="text-xs" style="color:var(--muted);">退出登录</a>
  </div>
</aside>

<!-- Main -->
<main class="flex-1 overflow-y-auto">
  <div class="px-7 py-7">
    <?= $content ?>
  </div>
</main>

</body>
</html>
