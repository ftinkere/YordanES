<?php

declare(strict_types=1);

namespace App\Helpers;

use DateTimeInterface;
use IntlDateFormatter;

class CommonHelper
{
    public static function formatDate(DateTimeInterface $date): false|string
    {
        return IntlDateFormatter::formatObject($date, [IntlDateFormatter::LONG, IntlDateFormatter::NONE], 'ru');
    }
}
