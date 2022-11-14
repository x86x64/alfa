<?php

namespace App\Services\Exchange;

class ExchangeHelper
{
    /**
     * Round an amount of a currency to a particular precision
     *
     * @param \ccxt\Exchange $exchange
     * @param string $currency
     * @param float $amount
     * @param float $roundedAmount
     */
    public static function preciseCurrencyAmount(&$exchange, $currency, $amount) {
        $precision = $exchange->currencies[ $currency ]['precision'];
        return round($amount, $precision, PHP_ROUND_HALF_DOWN);
    }

    /**
     * Round an amount of currency to be able to put an order on the exchange
     *
     * @param \ccxt\Exchange $exchange
     * @param string symbol
     * @param float $amount
     * @return float $roundedAmount
     */
    public static function preciseMarketAmount(&$exchange, $symbol, $amount) {
         return (float)$exchange->amount_to_precision($symbol, $amount);
    }
}
