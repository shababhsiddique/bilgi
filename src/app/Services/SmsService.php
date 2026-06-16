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

        $to = $this->normalizeRecipients($to);

        if ($to === '') {
            Log::error('SMS not sent: no valid recipient number after normalization.');

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
     * Normalize one or more (comma-separated) recipient numbers to the
     * format sms.net.bd expects: digits only, with the Bangladesh country
     * code (880). Drops anything that isn't a usable number.
     */
    protected function normalizeRecipients(string $to): string
    {
        return collect(explode(',', $to))
            ->map(fn (string $number): string => $this->normalizeNumber($number))
            ->filter()
            ->unique()
            ->implode(',');
    }

    /**
     * Normalize a single number to 880XXXXXXXXXX form.
     *
     * Handles inputs like "+8801712345678", "01712345678", "01712-345678",
     * "008801712345678" and "8801712345678".
     */
    protected function normalizeNumber(string $number): string
    {
        // Strip everything that isn't a digit (drops +, spaces, dashes, parens).
        $digits = preg_replace('/\D+/', '', $number) ?? '';

        if ($digits === '') {
            return '';
        }

        // International "00" prefix -> drop it.
        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }

        // Already country-coded.
        if (str_starts_with($digits, '880')) {
            return $digits;
        }

        // Local form 01XXXXXXXXX -> 8801XXXXXXXXX.
        if (str_starts_with($digits, '0')) {
            return '88' . $digits;
        }

        // Bare 1XXXXXXXXX (no leading 0) -> 8801XXXXXXXXX.
        if (str_starts_with($digits, '1') && strlen($digits) === 10) {
            return '880' . $digits;
        }

        return $digits;
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
