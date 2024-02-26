<?php

use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

Route::resources([
    'blogs'=>BlogController::class,
]);