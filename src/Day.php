<?php

declare(strict_types=1);

namespace Kayrunm\FluentRrule;

enum Day: string
{
    case MONDAY = 'MO';
    case TUESDAY = 'TU';
    case WEDNESDAY = 'WE';
    case THURSDAY = 'TH';
    case FRIDAY = 'FR';
    case SATURDAY = 'SA';
    case SUNDAY = 'SU';
}
