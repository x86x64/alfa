<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Exchange\ExchangeService;

class ExchangeController extends Controller
{
    /**
     * Show the exchange form
     *
     * @return \Illuminate\View\View
     */
    public function index() {
        return view('exchange.index');
    }

    /**
     * Check the best exchange options
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function check(Request $request) {
        $currencyFrom = trim($request->post('currency_from'));
        $currencyTo = trim($request->post('currency_to'));
        $amount = (float)$request->post('amount');
        $exchangeService = new ExchangeService('binance');

        $results = [];
        $strategies = $exchangeService->getExchangeStrategies($currencyFrom, $currencyTo, $amount);
        foreach ($strategies as $strategy) {
            $results[] = $strategy->getResult();
        }

        // Sort strategies by the largest amount we can get
        usort($results, function($a, $b){
            return $a['result_amount'] < $b['result_amount'];
        });

        $results = array_slice($results, 0, 11);

        return view('exchange.check', [
            'currencyFrom' => $currencyFrom,
            'currencyTo' => $currencyTo,
            'amount' => $amount,
            'exchangeStrategies' => $results
        ]);
    }
}
