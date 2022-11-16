<?php

namespace App\Services\Exchange;

use App\Services\Exchange\MarketAction;
use App\Services\Exchange\ExchangeStrategy;

class ExchangeService
{
    // Exchange object
    private $exchange;

    /**
     * Create a new exchange service
     *
     * @param string $exchangeName
     */
    public function __construct(string $exchangeName) {
        $exchangeClass = "\\ccxt\\$exchangeName";
        $this->exchange = new $exchangeClass();
        $this->exchange->load_markets();
        $this->exchange->fetch_currencies();
    }

    /**
     * Get exchange strategies for moving from currencyFrom to currencyTo
     *
     * @param string $currencyFrom
     * @param string $currencyTo
     * @param float $amount
     * @return array|ExchangeStrategy $exchangeStrategies
     */
    public function getExchangeStrategies(
        string $currencyFrom,
        string $currencyTo,
        float $amount
    ): array
    {
        $exchangeStrategies = [];
        $firstBuy = [];
        $firstSell = [];
        $thenBuy = [];
        $thenSell = [];

        foreach ($this->exchange->markets as $market) {
            if (!$market['active']) {
                continue;
            }
            if ($market['quote'] === $currencyFrom && $market['base'] === $currencyTo) {
                $exchangeStrategies[] = new ExchangeStrategy(
                    $currencyFrom,
                    $currencyTo,
                    $amount,
                    [[
                        'symbol' => $market['symbol'],
                        'action' => MarketAction::ACTION_BUY,
                    ]]
                );
                continue;
            } elseif ($market['quote'] === $currencyTo && $market['base'] === $currencyFrom) {
                $exchangeStrategies[] = new ExchangeStrategy(
                    $currencyFrom,
                    $currencyTo,
                    $amount,
                    [[
                        'symbol' => $market['symbol'],
                        'action' => MarketAction::ACTION_SELL,
                    ]]
                );
                continue;
            }
            if ($market['base'] === $currencyFrom) {
                $firstSell[$market['quote']] = [
                    'symbol' => $market['symbol'],
                    'action' => MarketAction::ACTION_SELL,
                ];
            } elseif ($market['quote'] === $currencyFrom) {
                $firstBuy[$market['base']] = [
                    'symbol' => $market['symbol'],
                    'action' => MarketAction::ACTION_BUY,
                ];
            } elseif ($market['base'] === $currencyTo) {
                $thenBuy[$market['quote']] = [
                    'symbol' => $market['symbol'],
                    'action' => MarketAction::ACTION_BUY,
                ];
            } elseif ($market['quote'] === $currencyTo) {
                $thenSell[$market['base']] = [
                    'symbol' => $market['symbol'],
                    'action' => MarketAction::ACTION_SELL,
                ];
            }
        }

        $firstSellThenBuy = array_intersect_key($firstSell, $thenBuy);
        $firstBuyThenBuy = array_intersect_key($firstBuy, $thenBuy);
        $firstSellThenSell = array_intersect_key($firstSell, $thenSell);
        $firstBuyThenSell = array_intersect_key($firstBuy, $thenSell);

        foreach ($firstSellThenBuy as $tempCurrencyName => $action) {
            $exchangeStrategies[] = new ExchangeStrategy(
                $currencyFrom,
                $currencyTo,
                $amount,
                [$action, $thenBuy[ $tempCurrencyName ]]
            );
        }
        foreach ($firstBuyThenBuy as $tempCurrencyName => $action) {
            $exchangeStrategies[] = new ExchangeStrategy(
                $currencyFrom,
                $currencyTo,
                $amount,
                [$action, $thenBuy[ $tempCurrencyName ]]
            );
        }
        foreach ($firstSellThenSell as $tempCurrencyName => $action) {
            $exchangeStrategies[] = new ExchangeStrategy(
                $currencyFrom,
                $currencyTo,
                $amount,
                [$action, $thenSell[ $tempCurrencyName ]]
            );
        }
        foreach ($firstBuyThenSell as $tempCurrencyName => $action) {
            $exchangeStrategies[] = new ExchangeStrategy(
                $currencyFrom,
                $currencyTo,
                $amount,
                [$action, $thenSell[ $tempCurrencyName ]]
            );
        }

        foreach ($exchangeStrategies as $exchangeStrategy) {
            $exchangeStrategy->runWithExchange($this->exchange);
        }

        return $exchangeStrategies;
    }
}
