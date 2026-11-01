<?php
declare(strict_types=1);
// Run: php database/migrate.php

require dirname(__DIR__) . '/bootstrap/app.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

$schema = Capsule::schema();

echo "Running migrations...\n";

// ── products ──────────────────────────────────────────────────────────────
$schema->create('as_products', function (Blueprint $table) {
    $table->id();
    $table->string('name', 120);
    $table->string('category', 50)->default('other')->index();
    $table->text('description')->nullable();
    $table->decimal('price', 10, 2);
    $table->decimal('original_price', 10, 2)->nullable();
    $table->tinyInteger('delivery_type')->default(1)->comment('1=auto 2=manual');
    $table->tinyInteger('is_active')->default(1)->index();
    $table->integer('sort')->default(0);
    $table->string('tag', 20)->nullable()->comment('热销/新品/推荐');
    $table->timestamps();
});
echo "  ✓ as_products\n";

// ── cards ─────────────────────────────────────────────────────────────────
$schema->create('as_cards', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('product_id')->index();
    $table->text('secret')->comment('卡密内容');
    $table->tinyInteger('status')->default(0)->index()->comment('0=unsold 1=sold');
    $table->unsignedBigInteger('order_id')->nullable()->index();
    $table->timestamp('sold_at')->nullable();
    $table->timestamp('created_at')->nullable();
    $table->foreign('product_id')->references('id')->on('as_products')->onDelete('cascade');
});
echo "  ✓ as_cards\n";

// ── orders ────────────────────────────────────────────────────────────────
$schema->create('as_orders', function (Blueprint $table) {
    $table->id();
    $table->string('trade_no', 64)->unique();
    $table->unsignedBigInteger('product_id')->index();
    $table->string('product_name', 120)->comment('下单时快照');
    $table->unsignedInteger('quantity')->default(1);
    $table->decimal('amount', 10, 2);
    $table->string('buyer_email', 200);
    $table->string('buyer_contact', 200)->nullable();
    $table->string('payment_channel', 50)->default('placeholder');
    $table->tinyInteger('status')->default(0)->index()->comment('0=待付款 1=已支付 2=已取消');
    $table->tinyInteger('delivery_status')->default(0)->index()->comment('0=未发货 1=已发货');
    $table->json('secrets')->nullable()->comment('已发卡密列表');
    $table->string('gateway_trade_no', 200)->nullable();
    $table->string('client_ip', 45)->nullable();
    $table->timestamp('paid_at')->nullable();
    $table->timestamps();
});
echo "  ✓ as_orders\n";

// ── payments ──────────────────────────────────────────────────────────────
$schema->create('as_payments', function (Blueprint $table) {
    $table->id();
    $table->string('name', 80);
    $table->string('handle', 80)->unique()->comment('wechat/alipay/usdt/placeholder');
    $table->tinyInteger('is_active')->default(0);
    $table->json('config')->nullable()->comment('占位: appid/secret等');
    $table->integer('sort')->default(0);
    $table->timestamps();
});
echo "  ✓ as_payments\n";

// ── admins ────────────────────────────────────────────────────────────────
$schema->create('as_admins', function (Blueprint $table) {
    $table->id();
    $table->string('username', 60)->unique();
    $table->string('password', 255);
    $table->timestamp('last_login_at')->nullable();
    $table->string('last_login_ip', 45)->nullable();
    $table->timestamps();
});
echo "  ✓ as_admins\n";

// ── seed default data ────────────────────────────────────────────────────
Capsule::table('as_admins')->insertOrIgnore([[
    'username'   => env('ADMIN_USER', 'admin'),
    'password'   => password_hash(env('ADMIN_PASS', 'admin123'), PASSWORD_BCRYPT),
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s'),
]]);
echo "  ✓ admin seeded\n";

Capsule::table('as_payments')->insertOrIgnore([
    ['name'=>'微信支付',  'handle'=>'wechat',      'is_active'=>0, 'config'=>json_encode(['appid'=>'','mch_id'=>'','api_key'=>'']), 'sort'=>1, 'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
    ['name'=>'支付宝',    'handle'=>'alipay',      'is_active'=>0, 'config'=>json_encode(['app_id'=>'','private_key'=>'','public_key'=>'']), 'sort'=>2, 'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
    ['name'=>'模拟支付',  'handle'=>'placeholder', 'is_active'=>1, 'config'=>json_encode([]), 'sort'=>99, 'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
]);
echo "  ✓ payments seeded\n";

Capsule::table('as_products')->insertOrIgnore([
    ['name'=>'ChatGPT Plus 1个月','category'=>'chatgpt','description'=>'GPT-4o 无限制 · DALL·E 3 · 高级数据分析','price'=>148.00,'original_price'=>199.00,'delivery_type'=>1,'is_active'=>1,'sort'=>1,'tag'=>'热销','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
    ['name'=>'Claude Pro 1个月',  'category'=>'claude', 'description'=>'Claude Opus 4.5 · 扩展上下文 · Projects 功能','price'=>138.00,'original_price'=>185.00,'delivery_type'=>1,'is_active'=>1,'sort'=>2,'tag'=>null,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
    ['name'=>'Grok Premium+ 1个月','category'=>'grok',  'description'=>'Grok 3 无限制 · 图像生成 · 早期功能','price'=>168.00,'original_price'=>220.00,'delivery_type'=>1,'is_active'=>1,'sort'=>3,'tag'=>'新品','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
    ['name'=>'Midjourney Standard 1个月','category'=>'midjourney','description'=>'15h Fast GPU · 无限 Relax · Stealth 模式','price'=>128.00,'original_price'=>175.00,'delivery_type'=>1,'is_active'=>1,'sort'=>4,'tag'=>'推荐','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
]);
echo "  ✓ sample products seeded\n";

echo "\nMigration complete!\n";
