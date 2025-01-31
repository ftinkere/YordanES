<?php

declare(strict_types=1);

use App\Livewire\AccountSettingsPage;
use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\IndexPage;
use App\Livewire\Languages\CreatePage as LanguagesCreatePage;
use App\Livewire\Languages\Dictionary\ViewPage as DictionaryViewPage;
use App\Livewire\Languages\IndexPage as LanguagesIndexPage;
use App\Livewire\Languages\UpdatePage as LanguagesUpdatePage;
use App\Livewire\Languages\ViewPage as LanguagesViewPage;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexPage::class);

// Auth
Route::get('/register', RegisterPage::class);
Route::get('/login', LoginPage::class);
Route::get('/forgot-password', ForgotPasswordPage::class);
Route::get('/reset-password/{uuid}', ResetPasswordPage::class);
Route::get('/logout', static function (UserService $userService) {
    if ($userService->logout()) {
        session()->invalidate();
        return redirect('/');
    }

    return redirect('/login');
});
Route::get('/confirm-email/{uuid}', static function ($uuid, UserService $userService) {
    $user = User::getByUuid($uuid);

    if ($user instanceof User && $userService->confirmEmail($user, $uuid)) {
        session()->flash('message', 'Почта подтверждена');
    } else {
        session()->flash('message', 'Ошибка подтверждения почты');
    }

    return redirect('/');
});


Route::get('/settings', AccountSettingsPage::class);

Route::prefix('/languages')->group(function (): void {
    Route::get('/', LanguagesIndexPage::class);
    Route::get('/create', LanguagesCreatePage::class)
        ->middleware('auth')
        ->middleware('can:create,App\Models\Language');

    Route::prefix('/{language}')
        ->middleware('can:view,language')
        ->group(function (): void {
            Route::get('/', LanguagesViewPage::class);
            Route::get('/edit', LanguagesUpdatePage::class)
                ->middleware('can:update,language');
            Route::get('/dictionary', DictionaryViewPage::class);
        });

});
