<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\DictionaryArticle;
use App\Models\Lexeme;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

class FileService
{
    public function uploadAvatar(UploadedFile $uploadedFile, User $user): false|string
    {
        return $this->uploadImageToEntity($uploadedFile, $user, 'avatars');
    }

    public function uploadImageToArticle(UploadedFile $uploadedFile, DictionaryArticle $article): false|string
    {
        return $this->uploadImageToEntity($uploadedFile, $article, 'articles');
    }

    public function uploadImageToLexeme(UploadedFile $uploadedFile, Lexeme $lexeme): false|string
    {
        return $this->uploadImageToEntity($uploadedFile, $lexeme, 'lexemes');
    }

    protected function uploadImageToEntity(UploadedFile $uploadedFile, mixed $entity, string $folder): false|string
    {
        $path = $folder . '/' . $entity->uuid;
        $fileName = Carbon::now()->timestamp . '.' . $uploadedFile->getClientOriginalExtension();

        return '/storage/' . $uploadedFile->storeAs($path, $fileName, 'public');
    }
}
