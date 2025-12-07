<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaymentService
{
    protected string $shopProcessId;
    protected string $publicKey;
    protected string $secretKey;
    protected string $sandboxUrl;
    protected string $productionUrl;

    public function __construct()
    {
        $this->publicKey     = config('bancard.public_key');
        $this->secretKey     = config('bancard.private_key');

        $this->sandboxUrl    = "https://vpos.infonet.com.py:8888";
        $this->productionUrl = "https://vpos.infonet.com.py";

        $this->shopProcessId = uniqid("PROC-");
    }

    /**
     * Genera la firma requerida por Bancard
     */
    private function makeSignature(string $data)
    {
        return hash_hmac('sha256', $data, $this->secretKey);
    }

    /**
     * Crear transacción para redirigir al checkout de Bancard
     */
    public function generateCheckout($order)
    {
        $amount = number_format($order->total, 2, '.', '');
        $description = "Pago de pedido {$order->order_number}";

        $payload = [
            "public_key" => $this->publicKey,
            "operation" => [
                "token"         => $this->shopProcessId,
                "shop_process_id" => $order->id,
                "amount"        => $amount,
                "currency"      => "PYG",
                "additional_data" => "",
                "description"   => $description,
                "return_url"    => route('payment.return'),
                "cancel_url"    => route('payment.cancel'),
            ]
        ];

        // Firma
        $signature = $this->makeSignature(json_encode($payload['operation']));

        $payload["signature"] = $signature;

        return [
            "payload" => $payload,
            "form_url" => $this->sandboxUrl . "/vpos/api/0.3/charge",
        ];
    }

    /**
     * Consultar estado de pago (POST-NOTIFY)
     */
    public function checkPaymentStatus($shopProcessId)
    {
        $payload = [
            "public_key" => $this->publicKey,
            "operation" => [
                "shop_process_id" => $shopProcessId
            ]
        ];

        $signature = $this->makeSignature(json_encode($payload['operation']));
        $payload["signature"] = $signature;

        $response = Http::post(
            $this->sandboxUrl . "/vpos/api/0.3/get_confirmation",
            $payload
        )->json();

        return $response;
    }
}
