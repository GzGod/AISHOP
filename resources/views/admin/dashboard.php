<?php
/** @var array $stats */
/** @var \Illuminate\Database\Eloquent\Collection $recent_orders */
ob_start();
?>
<div class="flex items-center justify-between mb-7">
  <h1 class="display font-bold text-2xl">仪表盘</h1>
  <a href="<?= url() ?>" class="btn-ghost">← 前台商城</a>
</div>

<!-- Stats grid -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-7">
  <?php
  $statCards = [
    ['今日营收',  '¥ '.number_format($stats['today_revenue'],2), '#F59E0B','rgba(245,158,11,.12)','rgba(245,158,11,.3)'],
    ['累计营收',  '¥ '.number_format($stats['total_revenue'],2), '#A78BFA','rgba(139,92,246,.12)','rgba(139,92,246,.3)'],
    ['已支付订单', $stats['paid_orders'],  '#34D399','rgba(16,185,129,.12)','rgba(16,185,129,.3)'],
    ['待处理发货', $stats['pending_delivery'], '#FCA5A5','rgba(239,68,68,.08)','rgba(239,68,68,.2)'],
    ['在售商品',  $stats['total_products'], '#A78BFA','rgba(139,92,246,.12)','rgba(139,92,246,.3)'],
    ['卡密总数',  $stats['total_cards'],   '#7C90AC','rgba(255,255,255,.05)','var(--border-s)'],
    ['未售卡密',  $stats['unsold_cards'],  '#34D399','rgba(16,185,129,.12)','rgba(16,185,129,.3)'],
    ['待付款订单', $stats['pending_orders'],'#FCD34D','rgba(245,158,11,.1)','rgba(245,158,11,.3)'],
  ];
  foreach($statCards as [$label,$val,$col,$bg,$br]): ?>
  <div class="card p-5">
    <div class="text-xs mb-2" style="color:var(--muted);"><?= $label ?></div>
    <div class="display font-bold text-2xl" style="color:<?= $col ?>;"><?= $val ?></div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Recent orders -->
<div class="card overflow-hidden">
  <div class="px-5 py-4 flex items-center justify-between border-b" style="border-color:var(--border-s);">
    <h2 class="display font-semibold">最近订单</h2>
    <a href="<?= url('admin/orders') ?>" class="text-xs" style="color:var(--cta3);">查看全部 →</a>
  </div>
  <div class="overflow-x-auto">
    <table>
      <thead><tr>
        <th>订单号</th><th>商品</th><th>金额</th><th>邮箱</th><th>支付状态</th><th>发货状态</th><th>时间</th>
      </tr></thead>
      <tbody>
      <?php foreach($recent_orders as $o): ?>
      <tr>
        <td><code style="color:var(--cta3);font-size:.78rem;"><?= htmlspecialchars($o->trade_no) ?></code></td>
        <td style="max-width:12rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($o->product_name) ?></td>
        <td style="color:#F59E0B;font-weight:600;">¥<?= number_format($o->amount,2) ?></td>
        <td style="color:var(--muted);"><?= htmlspecialchars($o->buyer_email) ?></td>
        <td><?php echo $o->status==1?'<span class="badge badge-ok">已支付</span>':'<span class="badge badge-warn">待付款</span>'; ?></td>
        <td><?php echo $o->delivery_status==1?'<span class="badge badge-ok">已发货</span>':'<span class="badge badge-muted">未发货</span>'; ?></td>
        <td style="color:var(--muted);font-size:.8rem;"><?= substr((string)$o->created_at,0,16) ?></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php
$content = ob_get_clean();
$title = '仪表盘';
require __DIR__.'/../layouts/admin.php';
