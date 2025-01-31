<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

class FileService
{
    public function uploadAvatar(UploadedFile $uploadedFile, User $user): false|string
    {
        $path = 'avatars';
        $fileName = $user->uuid . '_' . Carbon::now()->timestamp . '.' . $uploadedFile->getClientOriginalExtension();

        return '/storage/' . $uploadedFile->storeAs($path, $fileName, 'public');
    }
}
