<?php

declare(strict_types=1);

use App\Livewire\AccountSettingsPage;
use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\IndexPage;
use App\Livewire\Languages\CreatePage as LanguagesCreatePage;
use App\Livewire\Languages\Dictionary\CreatePage as DictionaryCreatePage;
use App\Livewire\Languages\Dictionary\IndexPage as DictionaryIndexPage;
use App\Livewire\Languages\Dictionary\UpdatePage as DictionaryUpdatePage;
use App\Livewire\Languages\Dictionary\ViewPage as DictionaryViewPage;
use App\Livewire\Languages\IndexPage as LanguagesIndexPage;
use App\Livewire\Languages\UpdatePage as LanguagesUpdatePage;
use App\Livewire\Languages\ViewPage as LanguagesViewPage;
use App\Models\User;
use Illuminate\Support\Facades\Route;

///
Route::get('/info', function () {
    return phpinfo();
});
///

Route::get('/', IndexPage::class);

// Auth
Route::get('/register', RegisterPage::class);
Route::get('/login', LoginPage::class);
Route::get('/forgot-password', ForgotPasswordPage::class);
Route::get('/reset-password/{uuid}', ResetPasswordPage::class);
Route::get('/logout', static function () {
    if (auth()->guest()) {
        return redirect('/login');
    }
    auth()->logout();
    session()->invalidate();
    return redirect('/');
});
Route::get('/confirm-email/{uuid}', static function ($uuid) {
    $user = User::getByUuid($uuid);

    $token = request()->get('token');

    if ($user instanceof User && $user->verifyEmail($token)) {
        session()->flash('message', 'Почта подтверждена');
    } else {
        session()->flash('message', 'Ошибка подтверждения почты');
    }

    return redirect('/');
});


Route::get('/settings', AccountSettingsPage::class);

Route::prefix('/languages')->name('languages')->group(function (): void {
    Route::get('/', LanguagesIndexPage::class)->name('.index');
    Route::get('/create', LanguagesCreatePage::class)->name('.create')
        ->middleware('auth')
        ->middleware('can:create,App\Models\Language');

    Route::prefix('/{language}')
        ->middleware('can:view,language')
        ->group(function (): void {
            Route::get('/', LanguagesViewPage::class)->name('.view');
            Route::get('/edit', LanguagesUpdatePage::class)->name('.update')
                ->middleware('can:update,language');
            Route::prefix('/dictionary')->name('.dictionary')->group(function (): void {
                Route::get('/', DictionaryIndexPage::class)->name('.index');
                Route::get('/create', DictionaryCreatePage::class)->name('.create')
                    ->middleware('can:update,language')
                    ->middleware('can:create,App\Models\DictionaryArticle');
            });
        });

});

Route::prefix('/dictionary')->name('languages.dictionary')->group(function () {
    Route::get('/{article}', DictionaryViewPage::class)->name('.view')
        ->middleware('can:view,article');
    Route::get('/{article}/edit', DictionaryUpdatePage::class)->name('.update')
        ->middleware('can:update,article');
});
