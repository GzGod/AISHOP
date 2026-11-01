<?php
/** @var \Illuminate\Database\Eloquent\Collection $cards */
/** @var \Illuminate\Database\Eloquent\Collection $products */
/** @var int|null $product_id */
ob_start();
?>
<div class="flex items-center justify-between mb-6">
  <h1 class="display font-bold text-2xl">卡密管理</h1>
</div>

<?php if(isset($_GET['imported'])): ?>
<div class="mb-4 px-4 py-3 rounded-lg text-sm" style="background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);color:#34D399;">
  成功导入 <?= (int)$_GET['imported'] ?> 条卡密
</div>
<?php endif; ?>

<!-- Import form -->
<div class="card p-6 mb-6">
  <h2 class="display font-semibold mb-4">批量导入卡密</h2>
  <form method="POST" action="<?= url('admin/cards/import') ?>" class="space-y-4">
    <div>
      <label class="text-sm font-medium mb-1.5 block">选择商品</label>
      <select name="product_id" class="w-full max-w-xs" required>
        <option value="">-- 请选择 --</option>
        <?php foreach($products as $p): ?>
        <option value="<?= $p->id ?>" <?= $product_id===$p->id?'selected':'' ?>><?= htmlspecialchars($p->name) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label class="text-sm font-medium mb-1.5 block">卡密内容（每行一条）</label>
      <textarea name="cards_text" rows="6" class="w-full font-mono text-sm" style="resize:vertical;" placeholder="XXXX-XXXX-XXXX-XXXX&#10;YYYY-YYYY-YYYY-YYYY"></textarea>
    </div>
    <button type="submit" class="btn-cta px-5 py-2">导入卡密</button>
  </form>
</div>

<!-- Card list -->
<div class="card overflow-hidden">
  <div class="px-5 py-4 flex items-center gap-3 border-b" style="border-color:var(--border-s);">
    <h2 class="display font-semibold">卡密列表</h2>
    <form method="GET" action="<?= url('admin/cards') ?>" class="flex gap-2 ml-auto">
      <select name="product_id" class="text-sm" onchange="this.form.submit()">
        <option value="">全部商品</option>
        <?php foreach($products as $p): ?>
        <option value="<?= $p->id ?>" <?= $product_id===$p->id?'selected':'' ?>><?= htmlspecialchars($p->name) ?></option>
        <?php endforeach; ?>
      </select>
    </form>
  </div>
  <div class="overflow-x-auto">
    <table>
      <thead><tr><th>ID</th><th>商品</th><th>卡密内容</th><th>状态</th><th>导入时间</th></tr></thead>
      <tbody>
      <?php foreach($cards as $c): ?>
      <tr>
        <td style="color:var(--muted);font-size:.8rem;"><?= $c->id ?></td>
        <td><?= htmlspecialchars($c->product?->name??'-') ?></td>
        <td><code class="text-xs font-mono" style="color:var(--cta3);"><?= htmlspecialchars(substr($c->secret,0,40)).(strlen($c->secret)>40?'…':'') ?></code></td>
        <td><?= $c->status===0?'<span class="badge badge-ok">未售</span>':'<span class="badge badge-muted">已售</span>' ?></td>
        <td style="color:var(--muted);font-size:.8rem;"><?= substr((string)$c->created_at,0,16) ?></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php
$content=ob_get_clean();$title='卡密管理';
require __DIR__.'/../../layouts/admin.php';
