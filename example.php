<?php

declare(strict_types=1);

use Kayrunm\FluentRrule\Frequency;
use Kayrunm\FluentRrule\Schedule;

require __DIR__ . '/vendor/autoload.php';

$schedule = (new Schedule(Frequency::DAILY))
    ->until(new DateTime('+1 year'))
;

echo $schedule->format() . "\n";
