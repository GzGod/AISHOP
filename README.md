# AIShop — AI 订阅代充平台

> 自助发卡 · 全自动发货 · 支持 ChatGPT、Claude、Grok 等主流 AI 订阅代充

---

## 技术栈

| 层 | 技术 |
|---|---|
| 语言 | PHP 8.1+ |
| ORM | Illuminate/Database (Eloquent) |
| 前端 | HTML + Tailwind CSS CDN（无构建步骤）|
| 数据库 | MySQL 8 / MariaDB 10.6 |
| 路由 | 自定义轻量路由（`routes/web.php`）|
| 视图 | 原生 PHP 模板 |

---

## 目录结构

```
AISHOP/
├── public/               # Web 根目录（Apache/Nginx 指向此处）
│   ├── index.php         # 前端控制器入口
│   └── .htaccess
├── app/
│   ├── Controllers/
│   │   ├── Shop/         # 商城前台（IndexController, ProductController, OrderController）
│   │   ├── Api/          # JSON API（OrderController, PaymentController, ProductController）
│   │   └── Admin/        # 管理后台（Auth, Dashboard, Product, Card, Order, Payment）
│   ├── Models/           # Eloquent 模型（Product, Card, Order, Payment, Admin）
│   ├── Services/
│   │   ├── OrderService.php           # 下单、自动发卡、手动发货
│   │   └── Gateways/
│   │       ├── PlaceholderGateway.php # 模拟支付（开箱即用）
│   │       ├── WechatGateway.php      # 微信支付（占位，填 .env 后可用）
│   │       └── AlipayGateway.php      # 支付宝（占位，填 .env 后可用）
│   └── Contracts/
│       └── PaymentGateway.php        # 支付网关接口
├── database/
│   └── migrate.php                   # 建表 + 初始数据
├── resources/views/
│   ├── layouts/          # 布局（app.php, admin.php）
│   ├── shop/             # 前台页面（index, product, order）
│   ├── admin/            # 后台页面（dashboard, products, cards, orders, payments）
│   ├── partials/         # 共用片段（footer, product_icon）
│   └── errors/           # 错误页（404, error）
├── routes/web.php        # 路由表
├── bootstrap/
│   ├── app.php           # 应用启动（env加载、DB连接、路由）
│   └── helpers.php       # 全局辅助函数
├── .env.example          # 环境变量模板
└── composer.json
```

---

## 快速开始

### 1. 安装依赖

```bash
composer install
```

### 2. 配置环境变量

```bash
cp .env.example .env
```

编辑 `.env`，至少填写数据库连接：

```
DB_HOST=127.0.0.1
DB_DATABASE=aishop
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 3. 建表（初始化数据库）

```bash
php database/migrate.php
```

这将自动创建所有表并写入演示商品和管理员账号（默认 `admin` / `admin123`）。

### 4. 启动（开发）

```bash
php -S localhost:8000 -t public
```

访问 [http://localhost:8000](http://localhost:8000) 查看商城。

管理后台：[http://localhost:8000/admin](http://localhost:8000/admin)

---

## Nginx 生产配置

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/aishop/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    # 保护敏感目录
    location ~* ^/(app|bootstrap|database|resources|vendor)/ { deny all; }
    location ~ /\.env { deny all; }
}
```

---

## 测试完整下单流程

1. 在后台导入几条卡密：`/admin/cards` → 选商品 → 输入卡密 → 导入
2. 前台选商品 → 填邮箱 → 选「模拟支付」→ 下单
3. 订单页点击「模拟支付成功」→ 自动发卡
4. 确认页展示卡密，`storage/logs/delivery.log` 记录发货详情

---

## 接入真实支付

### 微信支付

在 `.env` 填入：
```
WECHAT_APP_ID=wx...
WECHAT_MCH_ID=1234567890
WECHAT_API_KEY=your32charkey
```

然后在 `app/Services/Gateways/WechatGateway.php` 的 `createPayUrl()` 方法中实现 Native/H5 下单 API 调用。

### 支付宝

在 `.env` 填入：
```
ALIPAY_APP_ID=2021...
ALIPAY_PRIVATE_KEY=MIIEow...
ALIPAY_PUBLIC_KEY=MIIBIj...
```

然后在 `app/Services/Gateways/AlipayGateway.php` 的 `createPayUrl()` 中实现 `alipay.trade.page.pay` API 调用。

---

## 核心流程

```
用户下单 POST /api/order/create
  └─ OrderService::create()       ← 创建 Order 记录
       └─ Gateway::createPayUrl() ← 生成支付 URL（占位返回订单页）
             ↓
用户支付（或点模拟支付）
             ↓
POST /api/payment/callback/{channel}
  └─ Gateway::verifyCallback()    ← 验证签名（占位返回 true）
       └─ OrderService::markPaid()
            └─ CardDelivery::deliverAuto()  ← SELECT FOR UPDATE 取卡密
                 └─ 更新 orders.secrets, delivery_status=1
                      └─ 写入 storage/logs/delivery.log（邮件占位）
```

---

## 待接入项（占位符）

| 功能 | 状态 | 文件 |
|------|------|------|
| 微信支付 | 占位 | `Services/Gateways/WechatGateway.php` |
| 支付宝 | 占位 | `Services/Gateways/AlipayGateway.php` |
| 邮件发货通知 | 占位（日志记录）| `Services/OrderService.php:deliverAuto()` |
| 管理员 2FA | 未实现 | `Controllers/Admin/AuthController.php` |

---

## 数据库表（前缀 `as_`）

| 表 | 说明 |
|---|---|
| `as_products` | 商品 |
| `as_cards` | 卡密库存 |
| `as_orders` | 订单 |
| `as_payments` | 支付渠道配置 |
| `as_admins` | 管理员账号 |
