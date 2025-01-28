<?php

namespace App\Helpers;

use DateTimeInterface;
use IntlDateFormatter;

class CommonHelper
{
    public static function formatDate(DateTimeInterface $date)
    {
        return IntlDateFormatter::formatObject($date, [IntlDateFormatter::LONG, IntlDateFormatter::NONE], 'ru');
    }
}
