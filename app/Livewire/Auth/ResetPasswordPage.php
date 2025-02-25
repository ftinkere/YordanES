<?php

namespace App\Livewire\Auth;

use App\Models\PasswordResetToken;
use App\Models\User;
use App\Services\UserService;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ResetPasswordPage extends Component
{
    #[Locked]
    public string $uuid;
    #[Locked]
    public string $token;
    #[Validate('required|min:8')]
    public string $password;
    #[Validate('required|min:8|same:password')]
    public string $password_repeat;

    public function resetPassword(UserService $service)
    {
        $this->validate();

        $user = User::getByUuid($this->uuid);
        if (! $user || ! $service->resetPassword($user, $this->password, $this->token)) {
            session()->flash('message', 'Ошибка восстановления пароля');
        }
        $this->redirect('/login');
    }

    public function mount($uuid)
    {
        $this->uuid = $uuid;
        $this->token = request()->query('token');

        $user = User::getByUuid($this->uuid);
        $token = PasswordResetToken::where('reset_token', $this->token)->first();
        if (! $user || ! $token || $token->user_uuid !== $user->uuid) {
            session()->flash('message', 'Ошибка восстановления пароля');
            $this->redirect('/');
        }
    }

    public function render()
    {
        return view('livewire.auth.reset-password-page');
    }
}
