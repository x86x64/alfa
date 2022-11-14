<?php

namespace App\Services\Exchange;

class MarketOrderBook {

    // Market Symbol
    private $symbol;

    // List of bids
    private $bids;

    // List of asks
    private $asks;

    /**
     * Create a new order book
     *
     * @param string $symbol
     */
    public function __construct(string $symbol) {
        $this->symbol = $symbol;
    }

    /**
     * Load aggregated order book data from an exchange
     *
     * @param \ccxt\Exchange $exchange
     * @return void
     */
    public function loadOrderBookFromExchange(\ccxt\Exchange $exchange): void {
        $orderBook = $exchange->fetch_l2_order_book($this->symbol, 20);
        foreach ($orderBook['bids'] as $bid) {
            $this->bids[] = [
                'price' => $bid[0],
                'amount' => $bid[1]
            ];
        }
        foreach ($orderBook['asks'] as $ask) {
            $this->asks[] = [
                'price' => $ask[0],
                'amount' => $ask[1]
            ];
        }
    }

    /**
     * Get a list of the bids
     *
     * @return array $bids
     */
    public function getBids(): array {
        return $this->bids;
    }

    /**
     * Get a list of the asks
     *
     * @return array $asks
     */
    public function getAsks(): array {
        return $this->asks;
    }
}
