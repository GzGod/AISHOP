<?php
/** @var \App\Models\Product $product */
ob_start();
$state = $product->stockState();
$badgeClass = match($state) { 'in'=>'badge-stock','few'=>'badge-few',default=>'badge-sold' };
$badgeLabel = match($state) { 'in'=>'有货','few'=>'库存紧张',default=>'售罄' };
$soldOut = $state === 'sold';
?>

<!-- NAV -->
<header class="fixed top-0 inset-x-0 z-50" id="site-nav">
  <nav class="mx-3 mt-3 flex items-center justify-between px-5 py-3 rounded-2xl"
       style="background:rgba(8,11,20,.88);border:1px solid rgba(30,45,69,.8);backdrop-filter:blur(18px);">
    <a href="<?= url() ?>" class="flex items-center gap-2.5">
      <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
           style="background:linear-gradient(135deg,#7C3AED,#5B21B6);box-shadow:0 0 0 1px rgba(139,92,246,.4);">
        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" class="w-4 h-4"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </div>
      <span style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:.95rem;">AI<span style="color:#F59E0B;">Shop</span></span>
    </a>
    <a href="<?= url() ?>" class="btn-ghost cursor-pointer px-4 py-2 rounded-lg text-sm font-medium">← 返回商城</a>
  </nav>
</header>

<section class="pt-28 pb-16 px-4">
  <div class="max-w-3xl mx-auto">
    <div class="card p-7">
      <div class="flex items-start gap-4 mb-5">
        <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0"
             style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.08);">
          <?php include __DIR__.'/../partials/product_icon.php'; ?>
        </div>
        <div class="flex-1">
          <div class="flex items-center gap-2 mb-1">
            <h1 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:1.5rem;"><?= htmlspecialchars($product->name) ?></h1>
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full <?= $badgeClass ?>"><?= $badgeLabel ?></span>
          </div>
          <p class="text-sm" style="color:var(--muted);"><?= htmlspecialchars($product->description ?? '') ?></p>
        </div>
      </div>

      <div class="flex items-end gap-3 mb-6 pb-6 border-b border-solid" style="border-color:var(--border-s);">
        <div class="price-tag" style="font-size:2rem;"><?= format_price($product->price) ?></div>
        <?php if($product->original_price): ?>
        <div class="price-orig" style="font-size:1rem;">原价 <?= format_price($product->original_price) ?></div>
        <?php endif; ?>
      </div>

      <form id="purchase-form" class="space-y-4">
        <input type="hidden" name="product_id" value="<?= $product->id ?>">
        <input type="hidden" name="_token" value="<?= csrf_token() ?>">

        <div>
          <label class="text-sm font-medium mb-2 block">数量</label>
          <div class="flex items-center gap-3">
            <button type="button" class="qty-btn" onclick="changeQty(-1)" aria-label="减少数量">
              <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
            </button>
            <span id="qty-display" style="font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:1.1rem;width:2rem;text-align:center;">1</span>
            <button type="button" class="qty-btn" onclick="changeQty(1)" aria-label="增加数量">
              <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
            </button>
            <span class="text-xs ml-2" style="color:var(--muted);">当前库存：<?= $product->stockCount() ?> 张</span>
          </div>
        </div>

        <div>
          <label for="buyer-email" class="text-sm font-medium mb-2 block">接收邮箱 <span style="color:#EF4444;">*</span></label>
          <input type="email" id="buyer-email" name="buyer_email" placeholder="your@email.com" required
                 class="w-full px-4 py-3 rounded-xl text-sm focus:outline-none"
                 style="background:rgba(255,255,255,.06);border:1px solid var(--border);color:var(--text);">
        </div>

        <div>
          <label class="text-sm font-medium mb-2 block">支付方式</label>
          <div class="flex gap-2 flex-wrap">
            <label class="flex items-center gap-2 px-4 py-2.5 rounded-xl cursor-pointer transition-all duration-150" style="background:rgba(139,92,246,.15);border:1px solid rgba(139,92,246,.4);color:#fff;">
              <input type="radio" name="payment_channel" value="placeholder" checked class="accent-purple-500">
              模拟支付（测试）
            </label>
            <label class="flex items-center gap-2 px-4 py-2.5 rounded-xl cursor-pointer transition-all duration-150" style="background:rgba(255,255,255,.04);border:1px solid var(--border-s);color:var(--muted);">
              <input type="radio" name="payment_channel" value="wechat" disabled class="accent-green-500 opacity-50">
              微信支付（未开通）
            </label>
            <label class="flex items-center gap-2 px-4 py-2.5 rounded-xl cursor-pointer transition-all duration-150" style="background:rgba(255,255,255,.04);border:1px solid var(--border-s);color:var(--muted);">
              <input type="radio" name="payment_channel" value="alipay" disabled class="accent-blue-500 opacity-50">
              支付宝（未开通）
            </label>
          </div>
          <p class="text-xs mt-1.5" style="color:var(--muted2);">微信/支付宝将在接入真实密钥后启用</p>
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-solid" style="border-color:var(--border-s);">
          <div>
            <span class="text-xs" style="color:var(--muted);">合计</span>
            <div class="price-tag" id="total-display" style="font-size:1.6rem;"><?= format_price($product->price) ?></div>
          </div>
          <button type="submit" class="btn-cta px-8 py-3 rounded-xl text-sm font-semibold" <?= $soldOut?'disabled':'' ?>
                  style="box-shadow:0 0 0 1px rgba(139,92,246,.4),0 6px 20px -6px rgba(109,40,217,.5);">
            <?= $soldOut?'已售罄':'立即购买' ?>
          </button>
        </div>
      </form>
    </div>

    <div class="card p-6 mt-4">
      <h3 style="font-family:'Space Grotesk',sans-serif;font-weight:600;margin-bottom:.75rem;">商品说明</h3>
      <p class="text-sm leading-relaxed" style="color:var(--muted);">
        购买后系统将自动从库存中取出兑换码（CDK），即时发送至您填写的邮箱。请在对应平台"升级订阅"页面输入兑换码完成激活。如有问题请联系客服。
      </p>
    </div>
  </div>
</section>

<?php include __DIR__.'/../partials/footer.php'; ?>

<script>
const PRICE = <?= $product->price ?>;
const MAX_QTY = Math.min(10, <?= $product->stockCount() ?>);
let qty = 1;

function changeQty(delta){
  qty = Math.max(1, Math.min(MAX_QTY, qty + delta));
  document.getElementById('qty-display').textContent = qty;
  document.getElementById('total-display').textContent = '¥ ' + (PRICE * qty).toFixed(2);
}

document.getElementById('purchase-form').addEventListener('submit', async (e) => {
  e.preventDefault();
  const form = e.target;
  const btn = form.querySelector('button[type=submit]');
  btn.disabled = true;
  btn.textContent = '处理中...';
  try {
    const res = await fetch('<?= url('api/order/create') ?>', {
      method: 'POST',
      headers: {'Content-Type':'application/x-www-form-urlencoded'},
      body: new URLSearchParams({
        product_id: form.product_id.value,
        quantity: qty,
        buyer_email: form.buyer_email.value,
        payment_channel: form.querySelector('input[name=payment_channel]:checked')?.value || 'placeholder',
        _token: form._token.value,
      })
    });
    const data = await res.json();
    if(data.code === 0){
      window.location.href = data.pay_url;
    } else {
      alert(data.msg || '下单失败');
      btn.disabled = false;
      btn.textContent = '立即购买';
    }
  } catch(err){
    alert('网络错误，请重试');
    btn.disabled = false;
    btn.textContent = '立即购买';
  }
});
</script>
<?php
$content = ob_get_clean();
$title   = $product->name . ' — AIShop';
require __DIR__.'/../layouts/app.php';
