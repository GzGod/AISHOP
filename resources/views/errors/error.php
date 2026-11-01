<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= $code ?? 404 ?> — AIShop</title>
<style>
body{background:#0C0F1A;color:#F1F5F9;font-family:system-ui,sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;text-align:center;}
.code{font-family:'Space Grotesk',sans-serif;font-size:5rem;font-weight:700;color:#8B5CF6;line-height:1;}
.msg{color:#7C90AC;margin:.75rem 0 2rem;}
a{background:rgba(139,92,246,.15);border:1px solid rgba(139,92,246,.3);color:#A78BFA;padding:.6rem 1.5rem;border-radius:8px;text-decoration:none;}
a:hover{background:rgba(139,92,246,.25);}
</style>
</head>
<body>
<div>
  <div class="code"><?= (int)($code ?? 404) ?></div>
  <p class="msg"><?= htmlspecialchars($message ?? 'Page Not Found') ?></p>
  <a href="/">← 返回首页</a>
</div>
</body>
</html>
<?php echo ob_get_clean();
