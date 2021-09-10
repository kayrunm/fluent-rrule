<?php

declare(strict_types=1);

use Kayrunm\FluentRrule\Frequency;
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

    public function testMinutely(): void
    {
        $schedule = Schedule::minutely();

        self::assertSame('FREQ=MINUTELY', $schedule->format());
    }

    public function testEveryMinute(): void
    {
        $schedule = Schedule::everyMinute();

        self::assertSame('FREQ=MINUTELY', $schedule->format());
    }

    public function testEveryOtherMinute(): void
    {
        $schedule = Schedule::everyOtherMinute();

        self::assertSame('FREQ=MINUTELY;INTERVAL=2', $schedule->format());
    }

    public function testHourly(): void
    {
        $schedule = Schedule::hourly();

        self::assertSame('FREQ=HOURLY', $schedule->format());
    }

    public function testEveryHour(): void
    {
        $schedule = Schedule::everyHour();

        self::assertSame('FREQ=HOURLY', $schedule->format());
    }

    public function testEveryOtherHour(): void
    {
        $schedule = Schedule::everyOtherHour();

        self::assertSame('FREQ=HOURLY;INTERVAL=2', $schedule->format());
    }

    public function testDaily(): void
    {
        $schedule = Schedule::daily();

        self::assertSame('FREQ=DAILY', $schedule->format());
    }

    public function testEveryDay(): void
    {
        $schedule = Schedule::everyDay();

        self::assertSame('FREQ=DAILY', $schedule->format());
    }

    public function testEveryOtherDay(): void
    {
        $schedule = Schedule::everyOtherDay();

        self::assertSame('FREQ=DAILY;INTERVAL=2', $schedule->format());
    }

    public function testWeekly(): void
    {
        $schedule = Schedule::weekly();

        self::assertSame('FREQ=WEEKLY', $schedule->format());
    }

    public function testEveryWeek(): void
    {
        $schedule = Schedule::everyWeek();

        self::assertSame('FREQ=WEEKLY', $schedule->format());
    }

    public function testEveryOtherWeek(): void
    {
        $schedule = Schedule::everyOtherWeek();

        self::assertSame('FREQ=WEEKLY;INTERVAL=2', $schedule->format());
    }

    public function testMonthly(): void
    {
        $schedule = Schedule::monthly();

        self::assertSame('FREQ=MONTHLY', $schedule->format());
    }

    public function testEveryMonth(): void
    {
        $schedule = Schedule::everyMonth();

        self::assertSame('FREQ=MONTHLY', $schedule->format());
    }

    public function testEveryOtherMonth(): void
    {
        $schedule = Schedule::everyOtherMonth();

        self::assertSame('FREQ=MONTHLY;INTERVAL=2', $schedule->format());
    }

    public function testYearly(): void
    {
        $schedule = Schedule::yearly();

        self::assertSame('FREQ=YEARLY', $schedule->format());
    }

    public function testEveryYear(): void
    {
        $schedule = Schedule::everyYear();

        self::assertSame('FREQ=YEARLY', $schedule->format());
    }

    public function testEveryOtherYear(): void
    {
        $schedule = Schedule::everyOtherYear();

        self::assertSame('FREQ=YEARLY;INTERVAL=2', $schedule->format());
    }

    public function testEvery(): void
    {
        $schedule = Schedule::every(Frequency::DAILY);

        self::assertSame('FREQ=DAILY', $schedule->format());
    }

    public function testEveryNthSecond(): void
    {
        $schedule = Schedule::everyNthSecond(5);

        self::assertSame('FREQ=SECONDLY;INTERVAL=5', $schedule->format());
    }

    public function testEveryNthMinute(): void
    {
        $schedule = Schedule::everyNthMinute(5);

        self::assertSame('FREQ=MINUTELY;INTERVAL=5', $schedule->format());
    }

    public function testEveryNthHour(): void
    {
        $schedule = Schedule::everyNthHour(5);

        self::assertSame('FREQ=HOURLY;INTERVAL=5', $schedule->format());
    }

    public function testEveryNthDay(): void
    {
        $schedule = Schedule::everyNthDay(5);

        self::assertSame('FREQ=DAILY;INTERVAL=5', $schedule->format());
    }

    public function testEveryNthWeek(): void
    {
        $schedule = Schedule::everyNthWeek(5);

        self::assertSame('FREQ=WEEKLY;INTERVAL=5', $schedule->format());
    }

    public function testEveryNthMonth(): void
    {
        $schedule = Schedule::everyNthMonth(5);

        self::assertSame('FREQ=MONTHLY;INTERVAL=5', $schedule->format());
    }

    public function testEveryNthYear(): void
    {
        $schedule = Schedule::everyNthYear(5);

        self::assertSame('FREQ=YEARLY;INTERVAL=5', $schedule->format());
    }

    public function testEveryNth(): void
    {
        $schedule = Schedule::everyNth(Frequency::DAILY, 5);

        self::assertSame('FREQ=DAILY;INTERVAL=5', $schedule->format());
    }

    public function testOnSeconds(): void
    {
        $schedule = Schedule::daily()->onSeconds(1, 2, 5);

        self::assertSame('FREQ=DAILY;BYSECOND=1,2,5', $schedule->format());
    }

    public function testOnMinutes(): void
    {
        $schedule = Schedule::daily()->onMinutes(1, 2, 5);

        self::assertSame('FREQ=DAILY;BYMINUTE=1,2,5', $schedule->format());
    }

    public function testOnHours(): void
    {
        $schedule = Schedule::daily()->onHours(1, 2, 5);

        self::assertSame('FREQ=DAILY;BYHOUR=1,2,5', $schedule->format());
    }

    public function testUntilUtc(): void
    {
        $schedule = Schedule::daily()->until(new DateTimeImmutable('2020-01-01 00:00:00', new DateTimeZone('UTC')));

        self::assertSame('FREQ=DAILY;UNTIL=20200101T000000Z', $schedule->format());
    }

    public function testUntilNonUtc(): void
    {
        $this->markTestIncomplete();
    }

    public function testTimes(): void
    {
        $schedule = Schedule::daily()->times(5);

        self::assertSame('FREQ=DAILY;COUNT=5', $schedule->format());
    }
}
