<?php

namespace App\Services\contracts;

interface RandomInterface
{
    public function randomString(int $length = 16): string;
}
