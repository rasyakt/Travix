<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    protected $baseCurrency = 'IDR';
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.currency_api.key');
    }

    /**
     * Get supported currencies
     */
    public function getSupportedCurrencies(): array
    {
        return [
            'IDR' => ['name' => 'Indonesian Rupiah', 'symbol' => 'Rp', 'decimal' => 0],
            'USD' => ['name' => 'US Dollar', 'symbol' => '$', 'decimal' => 2],
            'EUR' => ['name' => 'Euro', 'symbol' => '€', 'decimal' => 2],
            'GBP' => ['name' => 'British Pound', 'symbol' => '£', 'decimal' => 2],
            'SGD' => ['name' => 'Singapore Dollar', 'symbol' => 'S$', 'decimal' => 2],
            'MYR' => ['name' => 'Malaysian Ringgit', 'symbol' => 'RM', 'decimal' => 2],
            'THB' => ['name' => 'Thai Baht', 'symbol' => '฿', 'decimal' => 2],
            'AUD' => ['name' => 'Australian Dollar', 'symbol' => 'A$', 'decimal' => 2],
            'JPY' => ['name' => 'Japanese Yen', 'symbol' => '¥', 'decimal' => 0],
            'CNY' => ['name' => 'Chinese Yuan', 'symbol' => '¥', 'decimal' => 2],
        ];
    }

    /**
     * Get exchange rates (cached for 1 hour)
     */
    public function getExchangeRates(): array
    {
        return Cache::remember('exchange_rates', 3600, function () {
            try {
                // Use free API or implement your preferred provider
                $response = Http::timeout(10)->get('https://api.exchangerate-api.com/v4/latest/IDR');

                if ($response->successful()) {
                    return $response->json()['rates'] ?? $this->getFallbackRates();
                }

                return $this->getFallbackRates();

            } catch (\Exception $e) {
                Log::error('Failed to fetch exchange rates', ['error' => $e->getMessage()]);
                return $this->getFallbackRates();
            }
        });
    }

    /**
     * Convert amount from IDR to target currency
     */
    public function convert(float $amountIDR, string $targetCurrency): float
    {
        if ($targetCurrency === 'IDR') {
            return $amountIDR;
        }

        $rates = $this->getExchangeRates();
        
        if (!isset($rates[$targetCurrency])) {
            return $amountIDR;
        }

        $converted = $amountIDR * $rates[$targetCurrency];
        
        // Round based on currency decimal places
        $currencies = $this->getSupportedCurrencies();
        $decimals = $currencies[$targetCurrency]['decimal'] ?? 2;
        
        return round($converted, $decimals);
    }

    /**
     * Convert amount from any currency to IDR
     */
    public function convertToIDR(float $amount, string $fromCurrency): float
    {
        if ($fromCurrency === 'IDR') {
            return $amount;
        }

        $rates = $this->getExchangeRates();
        
        if (!isset($rates[$fromCurrency])) {
            return $amount;
        }

        return round($amount / $rates[$fromCurrency], 0);
    }

    /**
     * Format amount with currency symbol
     */
    public function format(float $amount, string $currency): string
    {
        $currencies = $this->getSupportedCurrencies();
        
        if (!isset($currencies[$currency])) {
            return number_format($amount, 0, ',', '.');
        }

        $config = $currencies[$currency];
        $formatted = number_format($amount, $config['decimal'], ',', '.');
        
        return $config['symbol'] . ' ' . $formatted;
    }

    /**
     * Get user's preferred currency from session/profile
     */
    public function getUserCurrency(): string
    {
        return session('currency', auth()->user()->preferred_currency ?? 'IDR');
    }

    /**
     * Set user's preferred currency
     */
    public function setUserCurrency(string $currency): void
    {
        if (!isset($this->getSupportedCurrencies()[$currency])) {
            return;
        }

        session(['currency' => $currency]);

        if (auth()->check()) {
            auth()->user()->update(['preferred_currency' => $currency]);
        }
    }

    /**
     * Fallback rates if API fails (approximate rates)
     */
    protected function getFallbackRates(): array
    {
        return [
            'USD' => 0.000063,
            'EUR' => 0.000058,
            'GBP' => 0.000050,
            'SGD' => 0.000085,
            'MYR' => 0.00028,
            'THB' => 0.0022,
            'AUD' => 0.000097,
            'JPY' => 0.0092,
            'CNY' => 0.00046,
        ];
    }
}
