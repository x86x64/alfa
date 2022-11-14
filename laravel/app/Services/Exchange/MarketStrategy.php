<?php

namespace App\Services\Exchange;

use App\Services\Exchange\MarketOrderBook;

class MarketStrategy
{
    // Market symbol
    private $symbol;

    // Market action (BUY or SELL)
    private $action;

    // Amount that has to be exchanged
    private $amount;

    // Final amount that we've got after exchanging
    private $resultAmount;

    // Final currency in which we've got the resultAmount
    private $resultCurrency;

    // Fee amount that we've paid during exchange process
    private $feeAmount;

    // Amount that we cannot exchange
    private $amountCantExchange;

    // Market data
    private $market;

    // The results of exchange iterations
    private $history;

    /**
     * Create a new market strategy
     *
     * @param string $symbol
     * @param int $action
     * @param float $amount
     */
    public function __construct(string $symbol, int $action, float $amount) {
        $this->symbol = $symbol;
        $this->action = $action;
        $this->amount = $amount;
    }

    /**
     * Run a market strategy
     *
     * @param \ccxt\Exchange $exchange
     * @return void
     */
    public function runWithExchange(\ccxt\Exchange &$exchange): void {

        $this->exchange = $exchange;
        $this->market = $exchange->markets[ $this->symbol ];

        $orderBook = new MarketOrderBook($this->symbol);
        $orderBook->loadOrderBookFromExchange($exchange);

        $orderBookList = ($this->action === MarketAction::ACTION_SELL)
            ? $orderBook->getBids()
            : $orderBook->getAsks();

        $this->resultCurrency = ($this->action === MarketAction::ACTION_SELL)
            ? $this->market['quote']
            : $this->market['base'];

        $amountToExchange = $this->amount;
        // $amountToExchange = ExchangeHelper::preciseMarketAmount(
        //     $this->exchange,
        //     $this->market['symbol'],
        //     $this->amount
        // );
        //$this->amountCantExchange = $this->amount - $amountToExchange;

        foreach ($orderBookList as $element) {
            if ($element['amount'] >= $amountToExchange) {
                $this->resultAmount += $this->performAction(
                    $this->action,
                    $amountToExchange,
                    $element['price']
                );
                break;
            } else {
                $this->resultAmount += $this->performAction(
                    $this->action,
                    $element['amount'],
                    $element['price']
                );
                $amountToExchange -= $element['amount'];
            }
            if ($amountToExchange == 0) {
                break;
            }
        }
    }

    /**
     * Perform an action (BUY or SELL)
     *
     * @param int $action
     * @param float $amount
     * @param float $price
     * @return float $receivedAmount
     */
    private function performAction($action, $amount, $price): float {
        if ($action == MarketAction::ACTION_SELL) {
            $exchangedAmount = $price * $amount;
            $precisionCurrency = $this->market['quote'];
        } else {
            $exchangedAmount = $amount / $price;
            $precisionCurrency = $this->market['base'];
        }
        $exchangedAmount = ExchangeHelper::preciseCurrencyAmount(
            $this->exchange,
            $precisionCurrency,
            $exchangedAmount
        );
        $feeAmount = ExchangeHelper::preciseCurrencyAmount(
            $this->exchange,
            $precisionCurrency,
            $this->market['taker'] * $exchangedAmount
        );
        $resultAmount = ExchangeHelper::preciseCurrencyAmount(
            $this->exchange,
            $precisionCurrency,
            $exchangedAmount - $feeAmount
        );

        $this->feeAmount += $feeAmount;

        $this->history[] = [
            'action' => $action,
            'amount' => $amount,
            'price' => $price,
            'exchanged_amount' => $exchangedAmount,
            'fee_amount' => $feeAmount,
            'result_amount' => $resultAmount
        ];

        return $resultAmount;
    }

    /**
     * Get the result of the market strategy
     *
     * @return array $result
     */
    public function getResult(): array {
        return [
            'symbol' => $this->symbol,
            'base' => $this->market['base'],
            'quote' => $this->market['quote'],
            'action' => $this->action,
            'action_name' => MarketAction::getName($this->action),
            'input_amount' => $this->amount,
            'result_amount' => $this->resultAmount,
            'result_currency' => $this->resultCurrency,
            'fee_amount' => $this->feeAmount,
            'left_amount' => $this->amountCantExchange,
            'history' => $this->history
        ];
    }
}
