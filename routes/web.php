<?php

use App\Http\Controllers\MainController;
use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\IndexPage;
use App\Services\UserService;
use Illuminate\Support\Facades\Route;

Route::get('/message', function () {
    return \Illuminate\Support\Facades\Request::get('text');
});

Route::get('/', IndexPage::class);

// Auth
Route::get('/register', RegisterPage::class);
Route::get('/login', LoginPage::class);
Route::get('/forgot-password', ForgotPasswordPage::class);
Route::get('/logout', static function (UserService $service) {
    if ($service->logout()) {
        return redirect('/');
    }
    return redirect('/login');
});
