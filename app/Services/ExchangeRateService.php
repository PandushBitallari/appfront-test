<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ExchangeRateService
{
    private const API_URL = 'https://open.er-api.com/v6/latest/USD';
    private const DEFAULT_CURRENCY = 'EUR';

    private Client $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getExchangeRate($currency = null): float
    {
        $currency = $currency ?? self::DEFAULT_CURRENCY;

        try {
            $response = $this->httpClient->get(self::API_URL);

            $data = json_decode($response->getBody(), true);

            if (isset($data['rates'][$currency])) {
                return $data['rates'][$currency];
            }

            throw new \RuntimeException('Exchange rate not found in response');

        } catch (GuzzleException|\JsonException|\RuntimeException $e) {
            return config('general.exchange_rate');
        }
    }
}
