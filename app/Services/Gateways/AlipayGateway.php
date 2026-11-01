<?php
declare(strict_types=1);

namespace App\Services\Gateways;

use App\Contracts\PaymentGateway;
use App\Models\Order;

/**
 * Alipay gateway — placeholder implementation.
 * Fill in real credentials via .env (ALIPAY_APP_ID / ALIPAY_PRIVATE_KEY / ALIPAY_PUBLIC_KEY).
 * @see https://opendocs.alipay.com/open/270/105898
 */
class AlipayGateway implements PaymentGateway
{
    private string $appId;
    private string $privateKey;
    private string $publicKey;
    private string $notifyUrl;

    public function __construct()
    {
        $this->appId      = env('ALIPAY_APP_ID', '');
        $this->privateKey = env('ALIPAY_PRIVATE_KEY', '');
        $this->publicKey  = env('ALIPAY_PUBLIC_KEY', '');
        $this->notifyUrl  = env('ALIPAY_NOTIFY_URL', url('api/payment/callback/alipay'));
    }

    public function createPayUrl(Order $order): string
    {
        // TODO: Implement Alipay PC/WAP trade.page.pay API call.
        // Required: appId, privateKey, order amount, out_trade_no, subject, notifyUrl.
        // Return: redirect URL to Alipay cashier.
        throw new \RuntimeException('Alipay not configured yet. Set ALIPAY_APP_ID, ALIPAY_PRIVATE_KEY, ALIPAY_PUBLIC_KEY in .env');
    }

    public function verifyCallback(array $payload, Order $order): bool
    {
        // TODO: Verify Alipay RSA2 signature from callback payload.
        return false;
    }

    public function label(): string { return '支付宝'; }
}
