<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Events\User\UserForgotPassword;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Application;
use Illuminate\Session\SessionManager;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Восстановление пароля')]
class ForgotPasswordPage extends Component
{
    #[Validate('required|email')]
    public string $email;
    public function __construct(private readonly SessionManager $sessionManager, private readonly Dispatcher $dispatcher, private readonly Factory $viewFactory)
    {
    }

    public function sendRecoveryLink(): void
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
        $this->sessionManager->flash('message', 'Вам направлено письмо со ссылкой для восстановления пароля');


        $this->dispatcher->dispatch(new UserForgotPassword($user->uuid));
    }

    public function render(): Factory|Application|View|\Illuminate\View\View
    {
        return $this->viewFactory->make('livewire.auth.forgot-password-page');
    }
}
