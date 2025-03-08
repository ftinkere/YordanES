<?php

namespace App\Livewire\Auth;

use App\Events\UserForgotPassword;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Восстановление пароля')]
class ForgotPasswordPage extends Component
{
    #[Validate('required')]
    public string $username;

    public function sendRecoveryLink(): void
    {
        $this->validate(messages: [
            'required' => 'Поле обязательно',
        ]);

        $user = User::where('username', $this->username ?? null)->first();
        if (! $user) {
            $this->dispatch('flash-message', 'Пользователя с таким никнеймом не найдено');
            return;
        }

        $this->redirect('/login');
        Session::flash('message', 'Вам направлено письмо со ссылкой для восстановления пароля');

        event(new UserForgotPassword($user->uuid));
    }

    public function render()
    {
        return view('livewire.auth.forgot-password-page');
    }
}
