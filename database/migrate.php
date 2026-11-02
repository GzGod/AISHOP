<?php
declare(strict_types=1);
// Auto-runs on container start; idempotent — safe to run multiple times.

require dirname(__DIR__) . '/bootstrap/app.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

$schema = Capsule::schema();

echo "Running migrations...\n";

// ── products ───────────────────────────────────────────────────────────────
if (!$schema->hasTable('as_products')) {
    $schema->create('as_products', function (Blueprint $table) {
        $table->id();
        $table->string('name', 120);
        $table->string('category', 50)->default('other')->index();
        $table->text('description')->nullable();
        $table->decimal('price', 10, 2);
        $table->decimal('original_price', 10, 2)->nullable();
        $table->tinyInteger('delivery_type')->default(1);
        $table->tinyInteger('is_active')->default(1)->index();
        $table->integer('sort')->default(0);
        $table->string('tag', 20)->nullable();
        $table->timestamps();
    });
    echo "  ✓ as_products created\n";
} else {
    echo "  ~ as_products already exists\n";
}

// ── cards ──────────────────────────────────────────────────────────────────
if (!$schema->hasTable('as_cards')) {
    $schema->create('as_cards', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('product_id')->index();
        $table->text('secret');
        $table->tinyInteger('status')->default(0)->index();
        $table->unsignedBigInteger('order_id')->nullable()->index();
        $table->timestamp('sold_at')->nullable();
        $table->timestamp('created_at')->nullable();
    });
    echo "  ✓ as_cards created\n";
} else {
    echo "  ~ as_cards already exists\n";
}

// ── orders ─────────────────────────────────────────────────────────────────
if (!$schema->hasTable('as_orders')) {
    $schema->create('as_orders', function (Blueprint $table) {
        $table->id();
        $table->string('trade_no', 64)->unique();
        $table->unsignedBigInteger('product_id')->index();
        $table->string('product_name', 120);
        $table->unsignedInteger('quantity')->default(1);
        $table->decimal('amount', 10, 2);
        $table->string('buyer_email', 200);
        $table->string('buyer_contact', 200)->nullable();
        $table->string('payment_channel', 50)->default('placeholder');
        $table->tinyInteger('status')->default(0)->index();
        $table->tinyInteger('delivery_status')->default(0)->index();
        $table->json('secrets')->nullable();
        $table->string('gateway_trade_no', 200)->nullable();
        $table->string('client_ip', 45)->nullable();
        $table->timestamp('paid_at')->nullable();
        $table->timestamps();
    });
    echo "  ✓ as_orders created\n";
} else {
    echo "  ~ as_orders already exists\n";
}

// ── payments ───────────────────────────────────────────────────────────────
if (!$schema->hasTable('as_payments')) {
    $schema->create('as_payments', function (Blueprint $table) {
        $table->id();
        $table->string('name', 80);
        $table->string('handle', 80)->unique();
        $table->tinyInteger('is_active')->default(0);
        $table->json('config')->nullable();
        $table->integer('sort')->default(0);
        $table->timestamps();
    });
    echo "  ✓ as_payments created\n";
} else {
    echo "  ~ as_payments already exists\n";
}

// ── admins ─────────────────────────────────────────────────────────────────
if (!$schema->hasTable('as_admins')) {
    $schema->create('as_admins', function (Blueprint $table) {
        $table->id();
        $table->string('username', 60)->unique();
        $table->string('password', 255);
        $table->timestamp('last_login_at')->nullable();
        $table->string('last_login_ip', 45)->nullable();
        $table->timestamps();
    });
    echo "  ✓ as_admins created\n";
} else {
    echo "  ~ as_admins already exists\n";
}

// ── seed ───────────────────────────────────────────────────────────────────
Capsule::table('as_admins')->insertOrIgnore([[
    'username'   => env('ADMIN_USER', 'admin'),
    'password'   => password_hash(env('ADMIN_PASS', 'admin123'), PASSWORD_BCRYPT),
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s'),
]]);
echo "  ✓ admin seeded\n";

Capsule::table('as_payments')->insertOrIgnore([
    ['name'=>'微信支付',  'handle'=>'wechat',      'is_active'=>0, 'config'=>json_encode(['appid'=>'','mch_id'=>'','api_key'=>'']),                      'sort'=>1,  'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
    ['name'=>'支付宝',    'handle'=>'alipay',      'is_active'=>0, 'config'=>json_encode(['app_id'=>'','private_key'=>'','public_key'=>'']),              'sort'=>2,  'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
    ['name'=>'模拟支付',  'handle'=>'placeholder', 'is_active'=>1, 'config'=>json_encode([]),                                                            'sort'=>99, 'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
]);
echo "  ✓ payments seeded\n";

Capsule::table('as_products')->insertOrIgnore([
    ['name'=>'ChatGPT Plus 1个月',       'category'=>'chatgpt',    'description'=>'GPT-4o 无限制 · DALL·E 3 · 高级数据分析',  'price'=>148,'original_price'=>199, 'delivery_type'=>1,'is_active'=>1,'sort'=>1,'tag'=>'热销',   'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
    ['name'=>'Claude Pro 1个月',          'category'=>'claude',     'description'=>'Claude Opus 4.5 · 扩展上下文 · Projects', 'price'=>138,'original_price'=>185, 'delivery_type'=>1,'is_active'=>1,'sort'=>2,'tag'=>null,      'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
    ['name'=>'Grok Premium+ 1个月',       'category'=>'grok',       'description'=>'Grok 3 无限制 · 图像生成 · 早期功能',      'price'=>168,'original_price'=>220, 'delivery_type'=>1,'is_active'=>1,'sort'=>3,'tag'=>'新品',   'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
    ['name'=>'Midjourney Standard 1个月', 'category'=>'midjourney', 'description'=>'15h Fast GPU · 无限 Relax · Stealth 模式','price'=>128,'original_price'=>175, 'delivery_type'=>1,'is_active'=>1,'sort'=>4,'tag'=>'推荐',   'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
]);
echo "  ✓ sample products seeded\n";

echo "\nMigration complete!\n";
