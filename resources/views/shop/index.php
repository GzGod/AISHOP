<?php
/** @var \App\Models\Product[] $products */
/** @var string $current */
ob_start();
require __DIR__.'/../partials/product_icon.php';
$cats = ['all'=>'全部商品','chatgpt'=>'ChatGPT','claude'=>'Claude','grok'=>'Grok','midjourney'=>'Midjourney','other'=>'其他'];
?>

<!-- NAV -->
<header class="fixed top-0 inset-x-0 z-50" id="site-nav">
  <nav class="mx-3 mt-3 flex items-center justify-between px-5 py-3 rounded-2xl"
       style="background:rgba(8,11,20,.88);border:1px solid rgba(30,45,69,.8);backdrop-filter:blur(18px);">
    <a href="<?= url() ?>" class="flex items-center gap-2.5">
      <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
           style="background:linear-gradient(135deg,#7C3AED,#5B21B6);box-shadow:0 0 0 1px rgba(139,92,246,.4);">
        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" class="w-4 h-4" aria-hidden="true">
          <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
      <span style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:.95rem;">AI<span style="color:#F59E0B;">Shop</span></span>
    </a>
    <ul class="hidden md:flex items-center gap-5 text-sm font-medium">
      <?php foreach($cats as $k=>$v): ?>
      <li><a href="<?= url('category/'.$k) ?>" class="nav-link<?= $current===$k?' active':'' ?>"><?= $v ?></a></li>
      <?php endforeach; ?>
    </ul>
    <a href="<?= url('admin/dashboard') ?>" class="btn-ghost cursor-pointer px-4 py-2 rounded-lg text-sm font-medium">管理后台</a>
  </nav>
</header>

<!-- HERO -->
<section class="relative mesh overflow-hidden px-4 pt-28 pb-12 text-center" aria-label="首页介绍">
  <div class="orb1" aria-hidden="true"></div>
  <div class="orb2" aria-hidden="true"></div>
  <div class="relative max-w-2xl mx-auto">
    <div class="chip inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-semibold mb-5">
      <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:#8B5CF6;" aria-hidden="true"></span>
      全自动发货 · 15秒到账
    </div>
    <h1 style="font-family:'Space Grotesk',sans-serif;font-size:clamp(2rem,5vw,3.5rem);font-weight:700;line-height:1.15;margin-bottom:1rem;">
      AI订阅代充<br><span style="color:#F59E0B;" class="glow-a">秒发兑换码</span>
    </h1>
    <p class="text-base mb-8 max-w-lg mx-auto" style="color:var(--muted);">
      支持 ChatGPT Plus / Pro、Claude Pro、Grok Premium 等主流订阅。无需翻墙、无需海外卡，充值后自助激活，全程不超过1分钟。
    </p>
    <div class="flex flex-wrap justify-center gap-x-8 gap-y-3 text-xs" style="color:var(--muted);">
      <?php foreach(['官方渠道充值，安全可靠','微信/支付宝付款','7×24 客服支持','10,000+ 买家好评'] as $t): ?>
      <div class="flex items-center gap-1.5">
        <svg viewBox="0 0 20 20" fill="#10B981" class="w-4 h-4" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <?= htmlspecialchars($t) ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CATEGORY FILTER -->
<section id="products" class="pt-4 pb-16 px-4">
  <div class="max-w-6xl mx-auto">
    <div class="flex flex-wrap gap-2 mb-8 reveal" role="group" aria-label="商品分类筛选">
      <?php foreach($cats as $k=>$v): ?>
      <a href="<?= url($k==='all'?'category/all':'category/'.$k) ?>"
         class="cat-btn<?= $current===$k?' active':'' ?>">
        <?= htmlspecialchars($v) ?>
      </a>
      <?php endforeach; ?>
    </div>

    <!-- PRODUCT GRID -->
    <?php if($products->isEmpty()): ?>
    <div class="text-center py-20" style="color:var(--muted);">
      <svg viewBox="0 0 64 64" fill="none" class="w-16 h-16 mx-auto mb-4 opacity-30" aria-hidden="true"><rect x="8" y="20" width="48" height="36" rx="4" stroke="#7C90AC" stroke-width="2"/><path d="M20 20v-4a12 12 0 0124 0v4" stroke="#7C90AC" stroke-width="2" stroke-linecap="round"/></svg>
      <p>该分类暂无商品</p>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" role="list">
      <?php foreach($products as $p): ?>
      <?php
        $state = $p->stockState();
        $badgeClass = match($state) { 'in'=>'badge-stock','few'=>'badge-few',default=>'badge-sold' };
        $badgeLabel = match($state) { 'in'=>'有货','few'=>'库存紧张',default=>'售罄' };
      ?>
      <div class="card p-5 flex flex-col gap-3 reveal" role="listitem">
        <div class="flex items-start justify-between gap-2">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
               style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.08);" aria-hidden="true">
            <?php echo product_icon($p); ?>
          </div>
          <div class="flex flex-col items-end gap-1">
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full <?= $badgeClass ?>"><?= $badgeLabel ?></span>
            <?php if($p->tag): ?>
            <span class="chip-a text-xs px-2 py-0.5 rounded-full font-semibold"><?= htmlspecialchars($p->tag) ?></span>
            <?php endif; ?>
          </div>
        </div>
        <div>
          <h3 style="font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:.9rem;line-height:1.3;"><?= htmlspecialchars($p->name) ?></h3>
        </div>
        <p class="text-xs leading-relaxed flex-1" style="color:var(--muted);"><?= htmlspecialchars($p->description ?? '') ?></p>
        <div class="flex items-end justify-between mt-auto pt-2 border-t border-solid" style="border-color:var(--border-s);">
          <div>
            <div class="price-tag"><?= format_price($p->price) ?></div>
            <?php if($p->original_price): ?>
            <div class="price-orig">原价 <?= format_price($p->original_price) ?></div>
            <?php endif; ?>
          </div>
          <?php if($state === 'sold'): ?>
          <button disabled class="btn-cta px-4 py-2 rounded-lg text-xs font-semibold" aria-disabled="true">售罄</button>
          <?php else: ?>
          <a href="<?= url('product/'.$p->id) ?>" class="btn-cta px-4 py-2 rounded-lg text-xs font-semibold cursor-pointer">立即购买</a>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="py-16 px-4" style="background:rgba(8,11,20,1);">
  <div class="max-w-4xl mx-auto text-center">
    <div class="chip inline-flex px-3 py-1 rounded-full text-xs font-semibold mb-4 reveal">使用流程</div>
    <h2 style="font-family:'Space Grotesk',sans-serif;font-size:2rem;font-weight:700;margin-bottom:3rem;" class="reveal">三步完成充值</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <?php foreach([
        ['1','选择商品下单','#6D28D9','rgba(109,40,217,.2)','rgba(139,92,246,.3)','选择你需要的订阅套餐，填写邮箱，用微信或支付宝完成付款。'],
        ['2','秒发兑换码','#34D399','rgba(16,185,129,.15)','rgba(16,185,129,.3)','付款后系统自动发货，兑换码同步推送到你的邮箱，通常不超过15秒。'],
        ['3','自助激活订阅','#FBBF24','rgba(245,158,11,.12)','rgba(245,158,11,.3)','登录对应平台，在订阅/升级页面输入兑换码即可激活，全程无需人工干预。'],
      ] as [$n,$title,$col,$bg,$br,$desc]): ?>
      <div class="card p-6 text-left reveal">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4"
             style="background:<?=$bg?>;border:1px solid <?=$br?>;color:<?=$col?>;font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:1.1rem;"><?=$n?></div>
        <h3 style="font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:.95rem;margin-bottom:.5rem;"><?=$title?></h3>
        <p class="text-sm" style="color:var(--muted);"><?=$desc?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include __DIR__.'/../partials/footer.php'; ?>

<script>
// Nav scroll
window.addEventListener('scroll',()=>{
  const n=document.getElementById('site-nav')?.firstElementChild;
  if(n) n.style.background=window.scrollY>40?'rgba(8,11,20,.96)':'rgba(8,11,20,.88)';
},{passive:true});
</script>
<?php
$content = ob_get_clean();
$title   = 'AIShop — AI订阅代充，秒发兑换码';
require __DIR__.'/../layouts/app.php';
