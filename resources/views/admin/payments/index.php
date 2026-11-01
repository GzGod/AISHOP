<?php
/** @var \Illuminate\Database\Eloquent\Collection $payments */
ob_start();
?>
<div class="flex items-center justify-between mb-6">
  <h1 class="display font-bold text-2xl">支付渠道</h1>
</div>

<?php if(isset($_GET['saved'])): ?>
<div class="mb-4 px-4 py-3 rounded-lg text-sm" style="background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);color:#34D399;">保存成功</div>
<?php endif; ?>

<div class="space-y-4">
<?php foreach($payments as $pm): ?>
<div class="card p-6">
  <div class="flex items-center justify-between mb-4">
    <div class="flex items-center gap-3">
      <h3 class="display font-semibold"><?= htmlspecialchars($pm->name) ?></h3>
      <span class="badge <?= $pm->is_active?'badge-ok':'badge-muted' ?>"><?= $pm->is_active?'已启用':'未启用' ?></span>
      <code class="text-xs" style="color:var(--muted);"><?= htmlspecialchars($pm->handle) ?></code>
    </div>
  </div>
  <form method="POST" action="<?= url('admin/payments/'.$pm->id) ?>" class="space-y-3">
    <div class="grid grid-cols-2 gap-3">
      <div>
        <label class="text-xs font-medium mb-1 block" style="color:var(--muted);">显示名称</label>
        <input type="text" name="name" value="<?= htmlspecialchars($pm->name) ?>" class="w-full">
      </div>
      <div>
        <label class="text-xs font-medium mb-1 block" style="color:var(--muted);">状态</label>
        <select name="is_active" class="w-full">
          <option value="1" <?= $pm->is_active?'selected':'' ?>>启用</option>
          <option value="0" <?= !$pm->is_active?'selected':'' ?>>禁用</option>
        </select>
      </div>
    </div>
    <?php
    $config = $pm->config ?? [];
    $fieldLabels = [
      'appid'          => 'App ID',
      'app_id'         => 'App ID',
      'mch_id'         => '商户号 (MCH_ID)',
      'api_key'        => 'API Key',
      'private_key'    => '私钥 (Private Key)',
      'public_key'     => '公钥 (Public Key)',
    ];
    ?>
    <?php if($pm->handle !== 'placeholder' && !empty($config)): ?>
    <div class="grid grid-cols-2 gap-3">
      <?php foreach($config as $k=>$v): ?>
      <div>
        <label class="text-xs font-medium mb-1 block" style="color:var(--muted);"><?= htmlspecialchars($fieldLabels[$k]??$k) ?></label>
        <input type="text" name="config[<?= htmlspecialchars($k) ?>]"
               value="<?= htmlspecialchars((string)$v) ?>"
               placeholder="<?= $v?'':'占位符，接入时填写' ?>"
               class="w-full text-sm font-mono">
      </div>
      <?php endforeach; ?>
    </div>
    <p class="text-xs" style="color:var(--muted2);">注意：真实密钥请妥善保管，不要提交到代码仓库。</p>
    <?php else: ?>
    <p class="text-xs" style="color:var(--muted2);">模拟支付渠道，无需配置密钥，用于开发测试。</p>
    <?php endif; ?>
    <button type="submit" class="btn-cta px-5 py-2">保存</button>
  </form>
</div>
<?php endforeach; ?>
</div>
<?php
$content=ob_get_clean();$title='支付渠道';
require __DIR__.'/../../layouts/admin.php';
