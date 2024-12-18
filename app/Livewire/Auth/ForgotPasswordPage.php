<?php

namespace App\Livewire\Auth;

use App\Events\User\UserForgotPassword;
use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Component;

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
            return;
        }

        event(new UserForgotPassword($user->ulid));
    }

    public function render()
    {
        return view('livewire.auth.forgot-password-page');
    }
}
