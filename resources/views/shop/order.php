<?php
/** @var \App\Models\Order $order */
ob_start();
?>

<!-- NAV -->
<header class="fixed top-0 inset-x-0 z-50">
  <nav class="mx-3 mt-3 flex items-center justify-between px-5 py-3 rounded-2xl"
       style="background:rgba(8,11,20,.92);border:1px solid rgba(30,45,69,.8);backdrop-filter:blur(18px);">
    <a href="<?= url() ?>" style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:.95rem;">AI<span style="color:#F59E0B;">Shop</span></a>
    <a href="<?= url() ?>" class="btn-ghost cursor-pointer px-4 py-2 rounded-lg text-sm font-medium">← 返回商城</a>
  </nav>
</header>

<section class="pt-28 pb-16 px-4 min-h-screen" id="order-section">
  <div class="max-w-lg mx-auto">

    <!-- Status card -->
    <?php if($order->isDelivered()): ?>
    <div class="card p-7 text-center mb-4">
      <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center"
           style="background:rgba(16,185,129,.15);border:2px solid rgba(16,185,129,.4);">
        <svg viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5" class="w-8 h-8"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </div>
      <h1 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:1.5rem;margin-bottom:.5rem;color:#34D399;">订单已完成</h1>
      <p class="text-sm" style="color:var(--muted);">兑换码已发送至 <strong style="color:var(--text);"><?= htmlspecialchars($order->buyer_email) ?></strong></p>
    </div>

    <!-- Secrets -->
    <div class="card p-6 mb-4">
      <h2 style="font-family:'Space Grotesk',sans-serif;font-weight:600;margin-bottom:1rem;">兑换码</h2>
      <?php foreach(($order->secrets ?? []) as $i => $secret): ?>
      <div class="flex items-center gap-3 mb-2">
        <code class="flex-1 px-4 py-2 rounded-lg text-sm font-mono break-all"
              style="background:rgba(139,92,246,.1);border:1px solid rgba(139,92,246,.25);color:#C4B5FD;"><?= htmlspecialchars($secret) ?></code>
        <button onclick="copySecret(this, <?= $i ?>)"
                class="btn-ghost px-3 py-2 rounded-lg text-xs flex-shrink-0 cursor-pointer" aria-label="复制">
          <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/><path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"/></svg>
        </button>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="card p-5 text-sm" style="color:var(--muted);">
      <h3 style="font-family:'Space Grotesk',sans-serif;font-weight:600;color:var(--text);margin-bottom:.5rem;">使用方法</h3>
      <p>1. 登录对应平台（ChatGPT / Claude / Grok 等）<br>
         2. 进入「升级订阅」或「礼品码兑换」页面<br>
         3. 粘贴兑换码并确认，订阅即刻生效</p>
    </div>

    <?php elseif($order->isPaid()): ?>
    <div class="card p-7 text-center mb-4">
      <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center"
           style="background:rgba(245,158,11,.12);border:2px solid rgba(245,158,11,.3);">
        <svg viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="2" class="w-8 h-8"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3" stroke-linecap="round"/></svg>
      </div>
      <h1 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:1.4rem;margin-bottom:.5rem;color:#FBBF24;">已支付，等待发货</h1>
      <p class="text-sm" style="color:var(--muted);">商品需人工发货，商家将尽快处理，兑换码发至您的邮箱。</p>
    </div>

    <?php else: ?>
    <div class="card p-7 text-center mb-4" id="pending-card">
      <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center"
           style="background:rgba(139,92,246,.12);border:2px solid rgba(139,92,246,.3);">
        <svg viewBox="0 0 24 24" fill="none" stroke="#A78BFA" stroke-width="2" class="w-8 h-8 animate-spin" style="animation:spin 2s linear infinite;"><circle cx="12" cy="12" r="10" stroke-opacity=".25"/><path d="M12 2a10 10 0 010 20" stroke-linecap="round"/></svg>
      </div>
      <h1 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:1.4rem;margin-bottom:.5rem;">等待支付</h1>
      <p class="text-sm mb-5" style="color:var(--muted);">
        订单号：<code style="color:var(--cta3);"><?= htmlspecialchars($order->trade_no) ?></code><br>
        应付金额：<strong style="color:#F59E0B;"><?= format_price($order->amount) ?></strong>
      </p>
      <a href="<?= url('order/'.$order->trade_no.'?mock=1') ?>"
         class="btn-cta inline-block px-8 py-3 rounded-xl text-sm font-semibold cursor-pointer"
         style="box-shadow:0 0 0 1px rgba(139,92,246,.4),0 6px 20px -6px rgba(109,40,217,.5);">
        模拟支付成功（测试用）
      </a>
      <p class="text-xs mt-3" style="color:var(--muted2);">真实支付接入后此按钮将替换为跳转支付链接</p>
    </div>
    <?php endif; ?>

    <!-- Order summary -->
    <div class="card p-5 text-sm mt-2">
      <h3 style="font-family:'Space Grotesk',sans-serif;font-weight:600;margin-bottom:.75rem;">订单信息</h3>
      <table style="width:100%;border-collapse:collapse;">
        <?php foreach([
            '商品名称' => $order->product_name,
            '数量'     => $order->quantity,
            '实付金额' => format_price($order->amount),
            '接收邮箱' => $order->buyer_email,
            '订单号'   => $order->trade_no,
            '下单时间' => $order->created_at,
        ] as $k=>$v): ?>
        <tr>
          <td style="color:var(--muted);padding:.3rem 0;width:5rem;"><?= $k ?></td>
          <td style="color:var(--text);font-weight:500;"><?= htmlspecialchars((string)$v) ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
</section>

<style>@keyframes spin{from{transform:rotate(0)}to{transform:rotate(360deg)}}</style>
<script>
const secrets = <?= json_encode($order->secrets ?? [], JSON_UNESCAPED_UNICODE) ?>;

function copySecret(btn, idx){
  navigator.clipboard.writeText(secrets[idx]).then(()=>{
    btn.textContent = '已复制';
    setTimeout(()=>btn.innerHTML='<svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/><path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"/></svg>', 2000);
  });
}

// Poll order status every 4s if still pending
<?php if(!$order->isPaid()): ?>
let pollCount = 0;
const timer = setInterval(async () => {
  pollCount++;
  if(pollCount > 60) { clearInterval(timer); return; }
  try {
    const r = await fetch('<?= url('api/order/state?trade_no='.urlencode($order->trade_no)) ?>');
    const d = await r.json();
    if(d.status >= 1) { clearInterval(timer); location.reload(); }
  } catch(e){}
}, 4000);
<?php endif; ?>
</script>
<?php
$content = ob_get_clean();
$title   = '订单详情 — AIShop';
require __DIR__.'/../layouts/app.php';
