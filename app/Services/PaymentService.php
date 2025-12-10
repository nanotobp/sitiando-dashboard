<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentService
{
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
    }

    /**
     * Genera firma requerida por Bancard (HMAC-SHA256)
     */
    private function makeSignature(string $json)
    {
        return hash_hmac('sha256', $json, $this->secretKey);
    }

    /**
     * Crea la transacción y retorna datos para el formulario
     */
    public function generateCheckout($order)
    {
        // Bancard NO usa decimales en moneda PYG
        $amount = number_format($order->total, 0, '', '');

        // UUID único para cada operación
        $token = (string) Str::uuid();

        $operation = [
            "token"           => $token,
            "shop_process_id" => $order->id,
            "amount"          => $amount,
            "currency"        => "PYG",
            "additional_data" => "",
            "description"     => "Pago de pedido {$order->order_number}",
            "return_url"      => route('payment.return'),
            "cancel_url"      => route('payment.cancel'),
        ];

        // Firma
        $signature = $this->makeSignature(json_encode($operation));

        return [
            "payload" => [
                "public_key" => $this->publicKey,
                "operation"  => $operation,
                "signature"  => $signature
            ],
            "form_url" => $this->sandboxUrl . "/vpos/api/0.3/charge",
        ];
    }

    /**
     * Confirmación posterior — Bancard notifica esta ruta vía POST
     */
    public function checkPaymentStatus($shopProcessId)
    {
        $operation = [
            "shop_process_id" => $shopProcessId
        ];

        $signature = $this->makeSignature(json_encode($operation));

        $response = Http::post(
            $this->sandboxUrl . "/vpos/api/0.3/get_confirmation",
            [
                "public_key" => $this->publicKey,
                "operation"  => $operation,
                "signature"  => $signature,
            ]
        );

        return $response->json();
    }
}
