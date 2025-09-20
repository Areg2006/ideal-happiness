<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class ExchangeSiteService
{
    private string $baseUrl;

    public function __construct()
    {
    }

    public static function getRates(string $base = 'USD'): array
    {

        try {
            $response = Http::get(config('services.exchange.base_url') . '/live', [
                'access_key' => config('services.exchange.api_key'),
            ]);

            if (!$response["success"]) {
                throw new \Exception('Ошибка при запросе к API. HTTP код: ' . $response->status());
            }

            $json = $response->json();
            if (empty($json['success'])) {
                throw new \Exception('API вернуло ошибку: ' . json_encode($json));
            }

            $quotes = $json['quotes'] ?? [];
            $rates = [];

            foreach ($quotes as $pair => $rate) {
                $currency = substr($pair, 3);
                $rates[$currency] = $rate;
            }

            return $rates;

        } catch
        (\Throwable $e) {
            Log::error("Exchange API error: " . $e->getMessage());

            return [];
        }
    }
}
