<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Thin client for the sms.net.bd HTTP API.
 *
 * Docs: https://sms.net.bd/api
 * Send endpoint: https://api.sms.net.bd/sendsms
 */
class SmsService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct(?string $apiKey = null, ?string $baseUrl = null)
    {
        $this->apiKey = $apiKey ?? (string) config('services.sms_net_bd.key');
        $this->baseUrl = rtrim($baseUrl ?? (string) config('services.sms_net_bd.base_url', 'https://api.sms.net.bd'), '/');
    }

    /**
     * Send an SMS to one or more recipients.
     *
     * @param  string  $to   Recipient number, or comma-separated list of numbers.
     * @param  string  $message  The message body.
     * @return bool  True when the gateway accepted the request.
     */
    public function send(string $to, string $message): bool
    {
        if ($this->apiKey === '') {
            Log::error('SMS not sent: sms.net.bd API key is not configured.');

            return false;
        }

        $payload = [
            'api_key' => $this->apiKey,
            'to' => $to,
            'msg' => $message,
        ];

        try {
            $response = Http::asForm()
                ->timeout(15)
                ->acceptJson()
                ->post("{$this->baseUrl}/sendsms", $payload);
        } catch (\Throwable $e) {
            Log::error('SMS request to sms.net.bd failed.', [
                'to' => $to,
                'exception' => $e->getMessage(),
            ]);

            return false;
        }

        $body = $response->json();

        // sms.net.bd returns error code 0 on success.
        if ($response->successful() && isset($body['error']) && (int) $body['error'] === 0) {
            Log::info('SMS sent via sms.net.bd.', [
                'to' => $to,
                'request_id' => $body['data']['request_id'] ?? null,
            ]);

            return true;
        }

        Log::error('sms.net.bd rejected the SMS.', [
            'to' => $to,
            'status' => $response->status(),
            'error' => $body['error'] ?? null,
            'msg' => $body['msg'] ?? $response->body(),
        ]);

        return false;
    }

    /**
     * Query the remaining balance on the account.
     *
     * @return float|null  Balance, or null when it could not be retrieved.
     */
    public function balance(): ?float
    {
        if ($this->apiKey === '') {
            return null;
        }

        try {
            $response = Http::acceptJson()
                ->timeout(15)
                ->get("{$this->baseUrl}/user/balance/", [
                    'api_key' => $this->apiKey,
                ]);
        } catch (\Throwable $e) {
            Log::error('Failed to fetch sms.net.bd balance.', ['exception' => $e->getMessage()]);

            return null;
        }

        $body = $response->json();

        if ($response->successful() && isset($body['error']) && (int) $body['error'] === 0) {
            return isset($body['data']['balance']) ? (float) $body['data']['balance'] : null;
        }

        return null;
    }
}
