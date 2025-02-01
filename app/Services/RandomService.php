<?php

namespace App\Services;

use App\Services\contracts\RandomInterface;
use Str;

class RandomService implements RandomInterface
{

    public function randomString(int $length = 16): string
    {
        return Str::random($length);
    }
}
