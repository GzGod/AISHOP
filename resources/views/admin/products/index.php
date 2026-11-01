<?php
/** @var \Illuminate\Database\Eloquent\Collection $products */
ob_start();
?>
<div class="flex items-center justify-between mb-6">
  <h1 class="display font-bold text-2xl">商品管理</h1>
  <a href="<?= url('admin/products/create') ?>" class="btn-cta">+ 新增商品</a>
</div>

<?php if(isset($_GET['saved'])): ?>
<div class="mb-4 px-4 py-3 rounded-lg text-sm" style="background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);color:#34D399;">保存成功</div>
<?php endif; ?>

<div class="card overflow-hidden">
  <div class="overflow-x-auto">
    <table>
      <thead><tr><th>ID</th><th>商品名</th><th>分类</th><th>售价</th><th>库存</th><th>状态</th><th>排序</th><th>操作</th></tr></thead>
      <tbody>
      <?php foreach($products as $p): ?>
      <tr>
        <td style="color:var(--muted);font-size:.8rem;"><?= $p->id ?></td>
        <td>
          <div style="font-weight:600;"><?= htmlspecialchars($p->name) ?></div>
          <?php if($p->tag): ?><span class="badge badge-warn" style="font-size:.7rem;"><?= htmlspecialchars($p->tag) ?></span><?php endif; ?>
        </td>
        <td><span class="badge badge-muted"><?= htmlspecialchars($p->category) ?></span></td>
        <td style="color:#F59E0B;font-weight:600;">¥<?= number_format($p->price,2) ?></td>
        <td>
          <?php $s=$p->stockState(); $sc=$p->stockCount(); ?>
          <span class="badge <?= $s==='in'?'badge-ok':($s==='few'?'badge-warn':'badge-danger') ?>"><?= $sc ?></span>
        </td>
        <td><?= $p->is_active?'<span class="badge badge-ok">上架</span>':'<span class="badge badge-muted">下架</span>' ?></td>
        <td style="color:var(--muted);"><?= $p->sort ?></td>
        <td class="flex items-center gap-2">
          <a href="<?= url('admin/products/'.$p->id.'/edit') ?>" class="btn-ghost" style="padding:.35rem .7rem;font-size:.78rem;">编辑</a>
          <form method="POST" action="<?= url('admin/products/'.$p->id.'/delete') ?>" onsubmit="return confirm('确定删除？')">
            <button type="submit" class="btn-danger" style="padding:.35rem .7rem;font-size:.78rem;">删除</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php
$content=ob_get_clean();$title='商品管理';
require __DIR__.'/../../layouts/admin.php';
