<?php
/** @var \Illuminate\Database\Eloquent\Collection $orders */
/** @var string|null $status */
ob_start();
?>
<div class="flex items-center justify-between mb-6">
  <h1 class="display font-bold text-2xl">订单管理</h1>
  <div class="flex gap-2 text-sm">
    <?php foreach([''=> '全部','0'=>'待付款','1'=>'已支付'] as $v=>$l): ?>
    <a href="<?= url('admin/orders'.($v!==''?'?status='.$v:'')) ?>"
       class="px-3 py-1.5 rounded-lg transition-all"
       style="<?= $status===$v?'background:rgba(139,92,246,.2);border:1px solid rgba(139,92,246,.4);color:#fff;':'background:rgba(255,255,255,.04);border:1px solid var(--border-s);color:var(--muted);' ?>">
      <?= $l ?>
    </a>
    <?php endforeach; ?>
  </div>
</div>

<?php if(isset($_GET['delivered'])): ?>
<div class="mb-4 px-4 py-3 rounded-lg text-sm" style="background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);color:#34D399;">发货成功</div>
<?php endif; ?>

<div class="card overflow-hidden">
  <div class="overflow-x-auto">
    <table>
      <thead><tr><th>订单号</th><th>商品</th><th>数量</th><th>金额</th><th>邮箱</th><th>支付</th><th>发货</th><th>时间</th><th>操作</th></tr></thead>
      <tbody>
      <?php foreach($orders as $o): ?>
      <tr>
        <td><code style="color:var(--cta3);font-size:.75rem;"><?= htmlspecialchars($o->trade_no) ?></code></td>
        <td style="max-width:10rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($o->product_name) ?></td>
        <td style="color:var(--muted);"><?= $o->quantity ?></td>
        <td style="color:#F59E0B;font-weight:600;">¥<?= number_format($o->amount,2) ?></td>
        <td style="color:var(--muted);font-size:.82rem;"><?= htmlspecialchars($o->buyer_email) ?></td>
        <td><?= $o->status==1?'<span class="badge badge-ok">已支付</span>':'<span class="badge badge-warn">待付款</span>' ?></td>
        <td><?= $o->delivery_status==1?'<span class="badge badge-ok">已发</span>':'<span class="badge badge-muted">未发</span>' ?></td>
        <td style="color:var(--muted);font-size:.78rem;"><?= substr((string)$o->created_at,0,16) ?></td>
        <td>
          <?php if($o->status==1 && $o->delivery_status==0): ?>
          <button onclick="openDeliver(<?= $o->id ?>,<?= htmlspecialchars(json_encode($o->trade_no)) ?>)"
                  class="btn-cta" style="padding:.35rem .7rem;font-size:.78rem;">手动发货</button>
          <?php elseif($o->delivery_status==1): ?>
          <button onclick="viewSecrets(<?= htmlspecialchars(json_encode($o->secrets??[])) ?>)"
                  class="btn-ghost" style="padding:.35rem .7rem;font-size:.78rem;">查看卡密</button>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Manual deliver modal -->
<div id="deliver-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.7);backdrop-filter:blur(6px);">
  <div class="card p-6 w-full max-w-md">
    <h3 class="display font-semibold mb-1">手动发货</h3>
    <p id="deliver-trade-no" class="text-xs mb-4" style="color:var(--muted);"></p>
    <form method="POST" id="deliver-form">
      <label class="text-sm font-medium mb-1.5 block">卡密（每行一条）</label>
      <textarea name="secrets_text" rows="4" class="w-full font-mono text-sm mb-4" style="resize:vertical;"></textarea>
      <div class="flex gap-3">
        <button type="submit" class="btn-cta px-5 py-2">确认发货</button>
        <button type="button" onclick="closeDeliver()" class="btn-ghost px-5 py-2">取消</button>
      </div>
    </form>
  </div>
</div>

<!-- Secrets viewer modal -->
<div id="secrets-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.7);backdrop-filter:blur(6px);">
  <div class="card p-6 w-full max-w-md">
    <h3 class="display font-semibold mb-3">已发卡密</h3>
    <div id="secrets-list" class="space-y-2 mb-4 font-mono text-sm" style="color:var(--cta3);"></div>
    <button onclick="closeSecrets()" class="btn-ghost px-5 py-2">关闭</button>
  </div>
</div>

<script>
function openDeliver(id, tradeNo){
  document.getElementById('deliver-form').action = '<?= url('admin/orders/') ?>'+id+'/deliver';
  document.getElementById('deliver-trade-no').textContent = '订单号：'+tradeNo;
  const m = document.getElementById('deliver-modal');
  m.classList.remove('hidden'); m.style.display='flex';
}
function closeDeliver(){ const m=document.getElementById('deliver-modal'); m.classList.add('hidden');m.style.display=''; }
function viewSecrets(secrets){
  const list = document.getElementById('secrets-list');
  list.innerHTML = (secrets||[]).map(s=>`<div style="background:rgba(139,92,246,.1);border:1px solid rgba(139,92,246,.2);padding:.5rem .75rem;border-radius:8px;">${s}</div>`).join('');
  const m=document.getElementById('secrets-modal'); m.classList.remove('hidden');m.style.display='flex';
}
function closeSecrets(){ const m=document.getElementById('secrets-modal');m.classList.add('hidden');m.style.display=''; }
</script>
<?php
$content=ob_get_clean();$title='订单管理';
require __DIR__.'/../../layouts/admin.php';
