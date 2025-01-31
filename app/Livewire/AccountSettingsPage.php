<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Aggregates\UserAggregate;
use App\Models\User;
use App\Services\FileService;
use App\Services\UserService;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\View\Factory;
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
    public function __construct(private readonly Guard $guard, private readonly Factory $viewFactory)
    {
    }

    public string $name;

    #[Validate('email')]
    public string $email;

    /** @var UploadedFile $avatar */
    #[Validate('file|image|max:10240')]
    public mixed $avatar;

    public function mount(): void
    {
        $user = $this->guard->user();
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
        $this->user = $this->guard->user();
    }

    #[On('livewire-upload-finish')]
    public function avatarUpload(FileService $fileService): void
    {
        $this->validate(attributes: ['avatar']);
        $path = $fileService->uploadAvatar($this->avatar, $this->user);
        if ($path) {
            UserAggregate::retrieve($this->user->uuid)
                ->setAvatar($path)
                ->persist();
        }

        $this->user = $this->guard->user();
    }

    public function resendEmailConfirmation(UserService $userService): void
    {
        $userService->sendConfirmationEmail($this->user);
    }

    public function render()
    {
        return $this->viewFactory->make('livewire.account-settings-page');
    }
}
