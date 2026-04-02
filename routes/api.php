<?php

use App\Http\Controllers\FridgeController;
use Illuminate\Support\Facades\Route;

Route::apiResource('fridge', FridgeController::class);