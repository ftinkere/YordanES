<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;

class FileService
{
    public function uploadAvatar(UploadedFile $avatar, User $user): false|string
    {
        $path = 'avatars';
        $fileName = $user->uuid . '_' . time() . '.' . $avatar->getClientOriginalExtension();

        return '/storage/' . $avatar->storeAs($path, $fileName, 'public');
    }
}
