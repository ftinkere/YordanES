<?php

namespace App\Policies;

use App\Models\DictionaryArticle;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    public function before(?User $user): ?bool
    {
        if ($user?->isAdmin()) {
            return true;
        }

        return null;
    }

    public function view(?User $user, DictionaryArticle $dictionaryArticle): bool
    {
        return $dictionaryArticle->language->is_published || $dictionaryArticle->is_published;

    }

    public function create(User $user): bool
    {
        return $user->isVerified();
    }

    public function update(User $user, DictionaryArticle $dictionaryArticle): bool
    {
        return $dictionaryArticle->language->isAuthor($user);
    }

    public function delete(User $user, DictionaryArticle $dictionaryArticle): bool
    {
        return $dictionaryArticle->language->isAuthor($user);
    }

    public function restore(User $user, DictionaryArticle $dictionaryArticle): bool
    {
        return $dictionaryArticle->language->isAuthor($user);
    }

    public function forceDelete(User $user, DictionaryArticle $dictionaryArticle): bool
    {
        return $dictionaryArticle->language->isAuthor($user);
    }
}
