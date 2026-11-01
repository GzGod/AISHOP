<?php
declare(strict_types=1);

namespace App\Services\Gateways;

use App\Contracts\PaymentGateway;
use App\Models\Order;

/**
 * WeChat Pay gateway — placeholder implementation.
 * Fill in real credentials via .env (WECHAT_APP_ID / WECHAT_MCH_ID / WECHAT_API_KEY).
 * @see https://pay.weixin.qq.com/docs/merchant/apis/jsapi-payment/create.html
 */
class WechatGateway implements PaymentGateway
{
    private string $appId;
    private string $mchId;
    private string $apiKey;
    private string $notifyUrl;

    public function __construct()
    {
        $this->appId     = env('WECHAT_APP_ID', '');
        $this->mchId     = env('WECHAT_MCH_ID', '');
        $this->apiKey    = env('WECHAT_API_KEY', '');
        $this->notifyUrl = env('WECHAT_NOTIFY_URL', url('api/payment/callback/wechat'));
    }

    public function createPayUrl(Order $order): string
    {
        // TODO: Implement WeChat Pay Native/H5 API call.
        // Required: appId, mchId, apiKey, order amount, trade_no, notifyUrl.
        // Return: code_url (for QR) or h5_url (for mobile redirect).
        throw new \RuntimeException('WeChat Pay not configured yet. Set WECHAT_APP_ID, WECHAT_MCH_ID, WECHAT_API_KEY in .env');
    }

    public function verifyCallback(array $payload, Order $order): bool
    {
        // TODO: Verify WeChat Pay HMAC-SHA256 signature from callback payload.
        return false;
    }

    public function label(): string { return '微信支付'; }
}
