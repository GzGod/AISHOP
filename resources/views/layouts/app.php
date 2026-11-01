<?php
/** @var string $title */
/** @var string $content */
$isAdmin = str_contains($title ?? '', '管理') || str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin');
?>
<!DOCTYPE html>
<html lang="zh-CN" class="scroll-smooth">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($title ?? 'AIShop') ?></title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --bg:#0C0F1A;--bg2:#080B14;--surface:#111827;--raised:#162032;
  --border:#1E2D45;--border-s:#172135;
  --text:#F1F5F9;--muted:#7C90AC;--muted2:#4E637E;
  --accent:#F59E0B;--accent2:#FBBF24;
  --cta:#6D28D9;--cta2:#8B5CF6;--cta3:#A78BFA;
  --ok:#10B981;--warn:#F59E0B;--danger:#EF4444;
}
*{box-sizing:border-box;margin:0;padding:0;}
html{background:var(--bg);}
body{font-family:'DM Sans',system-ui,sans-serif;background:var(--bg);color:var(--text);line-height:1.6;font-size:16px;}
h1,h2,h3,h4,.display{font-family:'Space Grotesk',sans-serif;}
a{text-decoration:none;color:inherit;}
button{font-family:inherit;cursor:pointer;}
.card{background:linear-gradient(160deg,rgba(22,32,50,.9),rgba(17,24,39,.9));border:1px solid var(--border-s);border-radius:14px;transition:border-color .2s,transform .2s,box-shadow .2s;}
.card:hover{border-color:rgba(139,92,246,.4);box-shadow:0 8px 32px -8px rgba(139,92,246,.18);}
.btn-cta{background:linear-gradient(135deg,#7C3AED,#5B21B6);color:#fff;border:none;transition:filter .2s,box-shadow .2s;}
.btn-cta:hover{filter:brightness(1.1);box-shadow:0 0 0 1px rgba(139,92,246,.5),0 6px 20px -6px rgba(109,40,217,.6);}
.btn-cta:disabled{opacity:.45;cursor:not-allowed;}
.btn-ghost{background:transparent;border:1px solid var(--border);color:var(--text);transition:border-color .2s,color .2s;}
.btn-ghost:hover{border-color:var(--cta3);color:#fff;}
.btn-accent{background:linear-gradient(135deg,#D97706,#B45309);color:#fff;border:none;transition:filter .2s;}
.btn-accent:hover{filter:brightness(1.1);}
.badge-stock{background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.3);color:#34D399;}
.badge-sold{background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);color:#FCA5A5;}
.badge-few{background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);color:#FCD34D;}
.chip{background:rgba(139,92,246,.1);border:1px solid rgba(139,92,246,.22);color:var(--cta3);}
.chip-a{background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.22);color:var(--accent2);}
.nav-link{color:var(--muted);transition:color .18s;}
.nav-link:hover,.nav-link.active{color:var(--text);}
.cat-btn{background:rgba(255,255,255,.04);border:1px solid var(--border-s);color:var(--muted);border-radius:9999px;padding:.45rem 1.1rem;font-size:.82rem;font-weight:500;transition:all .18s;cursor:pointer;}
.cat-btn:hover,.cat-btn.active{background:rgba(139,92,246,.15);border-color:rgba(139,92,246,.4);color:#fff;}
.mesh{background-image:linear-gradient(rgba(148,163,184,.045) 1px,transparent 1px),linear-gradient(90deg,rgba(148,163,184,.045) 1px,transparent 1px);background-size:48px 48px;}
.orb1{position:absolute;top:-120px;left:50%;transform:translateX(-50%);width:600px;height:400px;background:radial-gradient(ellipse,rgba(109,40,217,.18),transparent 70%);filter:blur(40px);pointer-events:none;}
.orb2{position:absolute;top:60px;right:-100px;width:320px;height:320px;background:radial-gradient(circle,rgba(245,158,11,.12),transparent 70%);filter:blur(35px);pointer-events:none;}
.reveal{opacity:0;transform:translateY(14px);transition:opacity .55s ease,transform .55s ease;}
.reveal.in{opacity:1;transform:none;}
@media(prefers-reduced-motion:reduce){.reveal{opacity:1;transform:none;transition:none;}*{animation:none!important;transition:none!important;}}
:focus-visible{outline:2px solid var(--cta3);outline-offset:2px;border-radius:6px;}
::selection{background:rgba(139,92,246,.3);color:#fff;}
::-webkit-scrollbar{width:8px;}
::-webkit-scrollbar-track{background:var(--bg2);}
::-webkit-scrollbar-thumb{background:#1E2D45;border-radius:6px;}
.price-tag{font-family:'Space Grotesk',sans-serif;font-size:1.3rem;font-weight:700;color:var(--accent);}
.price-orig{text-decoration:line-through;color:var(--muted2);font-size:.82rem;}
.qty-btn{width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,.07);border:1px solid var(--border);color:var(--text);display:flex;align-items:center;justify-content:center;transition:background .15s;}
.qty-btn:hover{background:rgba(255,255,255,.13);}
.glow-a{text-shadow:0 0 16px rgba(245,158,11,.35);}
</style>
</head>
<body>
<?= $content ?>
<script>
// reveal observer
(function(){
  if(!('IntersectionObserver' in window)){document.querySelectorAll('.reveal').forEach(el=>el.classList.add('in'));return;}
  const obs=new IntersectionObserver(entries=>{entries.forEach(e=>{if(e.isIntersecting){e.target.classList.add('in');obs.unobserve(e.target);}});},{threshold:.1});
  document.querySelectorAll('.reveal').forEach(el=>obs.observe(el));
})();
</script>
</body>
</html>
