<?php

use App\Http\Controllers\RegisterDelegateController;

Route::post('register/delegate', RegisterDelegateController::class)->name('register.delegate');
