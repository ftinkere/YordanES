<?php

use App\Http\Controllers\MainController;
use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\IndexPage;
use App\Models\User;
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
Route::get('/reset-password/{uuid}', ResetPasswordPage::class);
Route::get('/logout', static function (UserService $service) {
    if ($service->logout()) {
        return redirect('/');
    }
    return redirect('/login');
});
Route::get('/confirm-email/{uuid}', static function ($uuid, UserService $service) {
    $user = User::getByUuid($uuid);

    if ($user && $service->confirmEmail($user, $uuid)) {
        session()->flash('message', 'Почта подтверждена');
    } else {
        session()->flash('message', 'Ошибка подтверждения почты');
    }
    return redirect('/');
});