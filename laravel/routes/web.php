<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExchangeController;
use Illuminate\Support\Facades\Storage;

Route::get('/', [ExchangeController::class, 'index'])
    ->name('exchange.index');

Route::post('/check', [ExchangeController::class, 'check'])
        ->name('exchange.check');
