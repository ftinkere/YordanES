<?php

use App\Events\User\UserLoggedOut;
use App\Http\Controllers\MainController;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\IndexPage;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

Route::get('/message', function () {
    return \Illuminate\Support\Facades\Request::get('text');
});

Route::get('/', IndexPage::class);

// Auth
Route::get('/register', RegisterPage::class);
Route::get('/login', LoginPage::class);
Route::get('/logout', function () {
    $user = Auth::user();
    if (! $user) {
        return redirect('/login');
    }
    event(new UserLoggedOut($user->ulid));

    return redirect('/');
});
