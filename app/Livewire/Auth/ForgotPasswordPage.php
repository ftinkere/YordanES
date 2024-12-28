<?php

namespace App\Livewire\Auth;

use App\Events\User\UserForgotPassword;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Восстановление пароля')]
class ForgotPasswordPage extends Component
{
    #[Validate('required|email')]
    public string $email;

    public function sendRecoveryLink()
    {
        $this->validate(messages: [
            'required' => 'Поле обязательно',
            'email' => 'Почта не почта',
        ]);

        $user = User::where('email', $this->email ?? null)->first();
        if (! $user) {
            $this->dispatch('flash-message', 'Пользователя с такой почтой не найдено');
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
