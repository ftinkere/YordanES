<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Exception;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Регистрация')]
class RegisterPage extends Component
{
    #[Validate('required|min:3')]
    public string $username;

    #[Validate('required')]
    public string $visible_name;

    #[Validate('required|email')]
    public string $email;

    #[Validate('required|min:8')]
    public string $password;

    #[Validate('required|same:password')]
    public string $password_repeat;

    /**
     * @throws Exception
     */
    public function register(): void
    {
        $this->validate();

        try {
            $user = User::register($this->username, $this->visible_name, $this->email, $this->password);
            $user->save();
            auth()->login($user);
            $this->redirect('/');
        } catch (Exception $e) {
            $this->addError('username', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.auth.register-page');
    }
}
