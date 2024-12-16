<?php

use App\Http\Controllers\MainController;
use App\Livewire\Auth\RegisterPage;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// TODO: DELETE TESTING
Route::get('/login-by-admin', function () {
    Auth::login(
        User::admin() ?? throw new NotFoundHttpException('Админ не найден'),
        remember: true,
    );

    return 'success';
});

Route::get('/message', function () {
    return \Illuminate\Support\Facades\Request::get('text');
});

////

Route::get('/', [MainController::class, 'index']);

// Auth
Route::get('/register', RegisterPage::class);
