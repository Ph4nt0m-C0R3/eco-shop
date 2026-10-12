<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\StripeController;

Route::post('/stripe/webhook', [StripeController::class, 'webhook']);
