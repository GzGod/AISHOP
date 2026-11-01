<?php
/** @var \App\Models\Product|null $product */
ob_start();
$editing = $product !== null;
$cats = ['chatgpt'=>'ChatGPT','claude'=>'Claude','grok'=>'Grok','midjourney'=>'Midjourney','other'=>'其他'];
?>
<div class="flex items-center gap-3 mb-6">
  <a href="<?= url('admin/products') ?>" class="btn-ghost" style="padding:.4rem .8rem;">← 返回</a>
  <h1 class="display font-bold text-xl"><?= $editing?'编辑商品':'新增商品' ?></h1>
</div>

<form method="POST" action="<?= $editing?url('admin/products/'.$product->id):url('admin/products') ?>" class="max-w-xl space-y-5">
  <div>
    <label class="text-sm font-medium mb-1.5 block">商品名称 *</label>
    <input type="text" name="name" value="<?= htmlspecialchars($product?->name??'') ?>" required class="w-full">
  </div>
  <div class="grid grid-cols-2 gap-4">
    <div>
      <label class="text-sm font-medium mb-1.5 block">分类</label>
      <select name="category" class="w-full">
        <?php foreach($cats as $k=>$v): ?>
        <option value="<?= $k ?>" <?= ($product?->category??'other')===$k?'selected':'' ?>><?= $v ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label class="text-sm font-medium mb-1.5 block">发货类型</label>
      <select name="delivery_type" class="w-full">
        <option value="1" <?= ($product?->delivery_type??1)===1?'selected':'' ?>>自动发卡</option>
        <option value="2" <?= ($product?->delivery_type??1)===2?'selected':'' ?>>人工发货</option>
      </select>
    </div>
  </div>
  <div class="grid grid-cols-2 gap-4">
    <div>
      <label class="text-sm font-medium mb-1.5 block">售价 *</label>
      <input type="number" name="price" step="0.01" min="0" value="<?= $product?->price??'' ?>" required class="w-full">
    </div>
    <div>
      <label class="text-sm font-medium mb-1.5 block">划线原价（可空）</label>
      <input type="number" name="original_price" step="0.01" min="0" value="<?= $product?->original_price??'' ?>" class="w-full">
    </div>
  </div>
  <div>
    <label class="text-sm font-medium mb-1.5 block">商品描述</label>
    <textarea name="description" rows="3" class="w-full" style="resize:vertical;"><?= htmlspecialchars($product?->description??'') ?></textarea>
  </div>
  <div class="grid grid-cols-3 gap-4">
    <div>
      <label class="text-sm font-medium mb-1.5 block">标签（可空）</label>
      <input type="text" name="tag" value="<?= htmlspecialchars($product?->tag??'') ?>" placeholder="热销/新品/推荐" class="w-full">
    </div>
    <div>
      <label class="text-sm font-medium mb-1.5 block">排序（小值优先）</label>
      <input type="number" name="sort" value="<?= $product?->sort??0 ?>" class="w-full">
    </div>
    <div>
      <label class="text-sm font-medium mb-1.5 block">状态</label>
      <select name="is_active" class="w-full">
        <option value="1" <?= ($product?->is_active??1)===1?'selected':'' ?>>上架</option>
        <option value="0" <?= ($product?->is_active??1)===0?'selected':'' ?>>下架</option>
      </select>
    </div>
  </div>
  <div class="flex gap-3 pt-2">
    <button type="submit" class="btn-cta px-6 py-2.5"><?= $editing?'保存修改':'创建商品' ?></button>
    <a href="<?= url('admin/products') ?>" class="btn-ghost px-6 py-2.5">取消</a>
  </div>
</form>
<?php
$content=ob_get_clean();$title=$editing?'编辑商品':'新增商品';
require __DIR__.'/../../layouts/admin.php';
