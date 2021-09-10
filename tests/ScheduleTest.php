<?php

declare(strict_types=1);

use Kayrunm\FluentRrule\Schedule;
use PHPUnit\Framework\TestCase;

final class ScheduleTest extends TestCase
{
    public function testSecondly(): void
    {
        $schedule = Schedule::secondly();

        self::assertSame('FREQ=SECONDLY', $schedule->format());
    }

    public function testEverySecond(): void
    {
        $schedule = Schedule::everySecond();

        self::assertSame('FREQ=SECONDLY', $schedule->format());
    }

    public function testEveryOtherSecond(): void
    {
        $schedule = Schedule::everyOtherSecond();

        self::assertSame('FREQ=SECONDLY;INTERVAL=2', $schedule->format());
    }
}
