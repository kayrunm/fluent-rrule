<?php

declare(strict_types=1);

namespace Kayrunm\FluentRrule;

enum Frequency
{
    case SECONDLY;
    case MINUTELY;
    case HOURLY;
    case DAILY;
    case WEEKLY;
    case MONTHLY;
    case YEARLY;

    public function format(): string
    {
        return match ($this) {
            self::SECONDLY => 'SECONDLY',
            self::MINUTELY => 'MINUTELY',
            self::HOURLY => 'HOURLY',
            self::DAILY => 'DAILY',
            self::WEEKLY => 'WEEKLY',
            self::MONTHLY => 'MONTHLY',
            self::YEARLY => 'YEARLY',
        };
    }
}
