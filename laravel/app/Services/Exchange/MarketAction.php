<?php

namespace App\Services\Exchange;

class MarketAction
{
    // Sell base currency in order to get quote currency
    public const ACTION_SELL = 0;

    // Buy base currency by selling quote qurrency
    public const ACTION_BUY = 1;

    // The names of the actions
    public const ACTIONS_NAMES = [
        self::ACTION_SELL => 'SELL',
        self::ACTION_BUY => 'BUY'
    ];

    /**
     * Get an action name
     *
     * @param int $action
     * @return string $actionName
     */
    public static function getName(int $action): string {
        return self::ACTIONS_NAMES[ $action ];
    }
}
