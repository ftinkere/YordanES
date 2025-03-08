<?php

namespace App\Livewire;

use App\Aggregates\UserAggregate;
use App\Aggregates\UserRepositoryAggregate;
use App\Events\UserEmailChanged;
use App\Models\User;
use App\Services\FileService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\UploadedFile;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

use function auth;

class AccountSettingsPage extends Component
{
    use WithFileUploads;

    #[Locked]
    public User $user;

    #[Validate('min:3')]
    public string $username;
    public string $name;
    #[Validate('email')]
    public string $email;

    #[Validate('file|image|max:10240')]
    public ?UploadedFile $avatar = null;

    public function mount(): void
    {
        $user = auth()->user();
        if (! $user) {
            $this->redirect('/login');
            return;
        }
        $this->user = $user;

        $this->username = $user->username;
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updated($property): void
    {
        try {
            $this->validateOnly($property);

            $user = User::findOrFail($this->user->uuid);
            switch ($property) {
                case 'username':
                    try {
                        $user->changeUsername($this->username);
                    } catch (Exception $e) {
                        $this->addError('username', $e->getMessage());
                    }
                    break;
                case 'name':
                    $user->name = $this->name;
                    break;
                case 'email':
                    $user->email = $this->email;
                    break;
            }
            $user->save();
        } finally {
            $user = auth()->user();

            $this->username = $user->username;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->user = auth()->user();
        }
    }

    #[On('livewire-upload-finish')]
    public function avatarUpload(FileService $service): void
    {
        if (! $this->avatar) {
            return;
        }
        $this->validateOnly('avatar');
        $path = $service->uploadAvatar($this->avatar, $this->user);
        if ($path) {
            $user = User::findOrFail($this->user->uuid);
            $user->avatar = $path;
            $user->save();
        }
        $this->user = auth()->user();
    }

    public function resendEmailConfirmation(): void
    {
        event(new UserEmailChanged($this->user->uuid));
    }

    public function render()
    {
        return view('livewire.account-settings-page');
    }
}
