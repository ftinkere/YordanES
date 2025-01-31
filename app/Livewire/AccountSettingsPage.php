<?php

namespace App\Livewire;

use App\Aggregates\UserAggregate;
use App\Models\User;
use App\Services\FileService;
use App\Services\UserService;
use Illuminate\Http\UploadedFile;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

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

    /** @var UploadedFile $avatar */
    #[Validate('file|image|max:10240')]
    public mixed $avatar;

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

    #[On('apply-setting')]
    public function changeAttribute(string $attribute): void
    {
        $this->validate(attributes: [$attribute]);

        $userAggregate = UserAggregate::retrieve($this->user->uuid);
        switch ($attribute) {
            case 'username':
                $userAggregate->changeUsername($this->username);
                break;
            case 'name':
                $userAggregate->changeName($this->name);
                break;
            case 'email':
                $userAggregate->changeEmail($this->email);
                break;
        }
        $userAggregate->persist();
        $this->user = auth()->user();
    }

    #[On('livewire-upload-finish')]
    public function avatarUpload(FileService $service): void
    {
        $this->validate(attributes: ['avatar']);
        $path = $service->uploadAvatar($this->avatar, $this->user);
        if ($path) {
            UserAggregate::retrieve($this->user->uuid)
                ->setAvatar($path)
                ->persist();
        }
        $this->user = auth()->user();
    }

    public function resendEmailConfirmation(UserService $service): void
    {
        $service->sendConfirmationEmail($this->user);
    }

    public function render()
    {
        return view('livewire.account-settings-page');
    }
}
