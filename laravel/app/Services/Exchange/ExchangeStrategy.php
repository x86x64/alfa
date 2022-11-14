<?php

namespace App\Services\Exchange;

use App\Services\Exchange\ExchangeHelper;
use App\Services\Exchange\MarketStrategy;

class ExchangeStrategy
{
    // The currency we've got
    private $currencyFrom;

    // The currency we want to get
    private $currencyTo;

    // The amount of currency we've got
    private $amount;

    // The result amount
    private $resultAmount;

    // Exchange steps that have to be taken
    // in order to move from currencyFrom to currencyTo
    private $steps;

    // The results of the steps performed
    private $stepsHistory;

    /**
     *
     * @param string $currencyFrom
     * @param string $currencyTo
     * @param float $amount
     * @param array $steps
     */
    public function __construct(
        string $currencyFrom,
        string $currencyTo,
        float $amount,
        array $steps
    ) {
        $this->currencyFrom = $currencyFrom;
        $this->currencyTo = $currencyTo;
        $this->amount = $amount;
        $this->steps = $steps;
    }

    /**
     * Determine the strategy outcome
     *
     * @param \ccxt\Exchange $exchange
     */
    public function runWithExchange(\ccxt\Exchange &$exchange): void {
        // Get the input amount with a precision
        $amount = ExchangeHelper::preciseCurrencyAmount(
            $exchange,
            $this->currencyFrom,
            $this->amount
        );

        // Move through a list of steps (BUY or SELL)
        foreach ($this->steps as $step) {
            $marketStrategy = new MarketStrategy(
                $step['symbol'],
                $step['action'],
                $amount
            );
            $marketStrategy->runWithExchange($exchange);
            $marketStrategyResult = $marketStrategy->getResult();
            $this->stepsHistory[] = $marketStrategyResult;

            if ($this->currencyTo == $marketStrategyResult['result_currency']) {
                $this->resultAmount = $marketStrategyResult['result_amount'];
            } else {
                $amount = $marketStrategyResult['result_amount'];
            }
        }
    }

    /**
     * Get the results on the strategy
     *
     * @return array $result
     */
    public function getResult() {
        return [
            'result_amount' => $this->resultAmount,
            'steps' => $this->stepsHistory
        ];
    }
}
