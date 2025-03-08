<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Services\contracts\RandomInterface;
use DateTimeInterface;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use IntlDateFormatter;
use Ramsey\Uuid\UuidFactoryInterface;

class CommonHelper
{
    public static function uuid()
    {
        return app(UuidFactoryInterface::class)->uuid7();
    }

    public static function randomStr($length = 60)
    {
        return app(RandomInterface::class)->randomString($length);
    }

    public static function formatDate(DateTimeInterface $date): false|string
    {
        return IntlDateFormatter::formatObject($date, [IntlDateFormatter::LONG, IntlDateFormatter::NONE], 'ru');
    }

    public static function checkEmail($email): bool
    {
        $validator = new EmailValidator();
        if (! $validator->isValid($email, new RFCValidation)) {
            return false;
        }
        return true;
    }
}
