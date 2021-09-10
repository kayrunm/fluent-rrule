<?php

declare(strict_types=1);

namespace Kayrunm\FluentRrule;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

final class Schedule
{
    private Frequency $frequency;
    /** @var positive-int */
    private int $interval;
    private ?DateTimeImmutable $from = null;
    private ?DateTimeImmutable $until = null;
    /** @var positive-int|null */
    private ?int $times = null;
    /** @var array<array-key, int<0, 60>> */
    private array $onSeconds = [];
    /** @var array<array-key, int<0, 59>> */
    private array $onMinutes = [];
    /** @var array<array-key, int<0, 23>> */
    private array $onHours = [];
    /** @var array<array-key, Day> */
    private array $onDays = [];
    /** @var array<array-key, int<1, 31>> */
    private array $onMonthDays = [];
    /** @var array<array-key, int<1, 366>> */
    private array $onYearDays = [];
    /** @var array<array-key, int<1, 53>> */
    private array $onWeeks = [];
    /** @var array<array-key, Month> */
    private array $onMonths = [];
    private ?Day $weekStartDay = null;

    /**
     * @param positive-int $interval
     */
    private function __construct(Frequency $frequency, int $interval = 1)
    {
        $this->frequency = $frequency;
        $this->interval = $interval;
    }

    public static function secondly(): self
    {
        return new self(Frequency::SECONDLY);
    }

    public static function everySecond(): self
    {
        return self::secondly();
    }

    public static function everyOtherSecond(): self
    {
        return self::everyNthSecond(2);
    }

    public static function minutely(): self
    {
        return new self(Frequency::MINUTELY);
    }

    public static function everyMinute(): self
    {
        return self::minutely();
    }

    public static function everyOtherMinute(): self
    {
        return self::everyNthMinute(2);
    }

    public static function hourly(): self
    {
        return new self(Frequency::HOURLY);
    }

    public static function everyHour(): self
    {
        return self::hourly();
    }

    public static function everyOtherHour(): self
    {
        return self::everyNthHour(2);
    }

    public static function daily(): self
    {
        return new self(Frequency::DAILY);
    }

    public static function everyDay(): self
    {
        return self::daily();
    }

    public static function everyOtherDay(): self
    {
        return self::everyNthDay(2);
    }

    public static function weekly(): self
    {
        return new self(Frequency::WEEKLY);
    }

    public static function everyWeek(): self
    {
        return self::weekly();
    }

    public static function everyOtherWeek(): self
    {
        return self::everyNthWeek(2);
    }

    public static function monthly(): self
    {
        return new self(Frequency::MONTHLY);
    }

    public static function everyMonth(): self
    {
        return self::monthly();
    }

    public static function everyOtherMonth(): self
    {
        return self::everyNthMonth(2);
    }

    public static function yearly(): self
    {
        return new self(Frequency::YEARLY);
    }

    public static function everyYear(): self
    {
        return self::yearly();
    }

    public static function everyOtherYear(): self
    {
        return self::everyNthYear(2);
    }

    public static function every(Frequency $frequency): self
    {
        return new self($frequency);
    }

    /**
     * @param positive-int $n
     */
    public static function everyNthSecond(int $n): self
    {
        return new self(Frequency::SECONDLY, $n);
    }

    /**
     * @param positive-int $n
     */
    public static function everyNthMinute(int $n): self
    {
        return new self(Frequency::MINUTELY, $n);
    }

    /**
     * @param positive-int $n
     */
    public static function everyNthHour(int $n): self
    {
        return new self(Frequency::HOURLY, $n);
    }

    /**
     * @param positive-int $n
     */
    public static function everyNthDay(int $n): self
    {
        return new self(Frequency::DAILY, $n);
    }

    /**
     * @param positive-int $n
     */
    public static function everyNthWeek(int $n): self
    {
        return new self(Frequency::WEEKLY, $n);
    }

    /**
     * @param positive-int $n
     */
    public static function everyNthMonth(int $n): self
    {
        return new self(Frequency::MONTHLY, $n);
    }

    /**
     * @param positive-int $n
     */
    public static function everyNthYear(int $n): self
    {
        return new self(Frequency::YEARLY, $n);
    }

    /**
     * @param positive-int $n
     */
    public static function everyNth(Frequency $frequency, int $n): self
    {
        return new self($frequency, $n);
    }

    /**
     * @param int<0, 60> ...$seconds
     * @return $this
     */
    public function onSeconds(int ...$seconds): self
    {
        $this->onSeconds = $seconds;

        return $this;
    }

    /**
     * @param int<0, 59> ...$minutes
     * @return $this
     */
    public function onMinutes(int ...$minutes): self
    {
        $this->onMinutes = $minutes;

        return $this;
    }

    /**
     * @param int<0, 23> ...$hours
     * @return $this
     */
    public function onHours(int ...$hours): self
    {
        $this->onHours = $hours;

        return $this;
    }

    /**
     * @return $this
     */
    public function onDays(Day ...$days): self
    {
        // todo: support ordinals
        $this->onDays = $days;

        return $this;
    }

    /**
     * @param int<1, 31> ...$monthDays
     * @return $this
     */
    public function onMonthDays(int ...$monthDays): self
    {
        // todo: support ordinals
        $this->onMonthDays = $monthDays;

        return $this;
    }

    /**
     * @param int<1, 366> ...$yearDays
     * @return $this
     */
    public function onYearDays(int ...$yearDays): self
    {
        // todo: support ordinals
        $this->onYearDays = $yearDays;

        return $this;
    }

    /**
     * @param int<1, 53> ...$weeks
     * @return $this
     */
    public function onWeeks(int ...$weeks): self
    {
        // todo: support ordinals
        $this->onWeeks = $weeks;

        return $this;
    }

    /**
     * @return $this
     */
    public function onMonths(Month ...$months): self
    {
        // todo: support ordinals
        $this->onMonths = $months;

        return $this;
    }

    /**
     * @return $this
     */
    public function until(DateTimeInterface $until): self
    {
        $this->until = DateTimeImmutable::createFromInterface($until);

        return $this;
    }

    /**
     * @param positive-int $times
     * @return $this
     */
    public function times(int $times): self
    {
        $this->times = $times;

        return $this;
    }

    /**
     * @return $this
     */
    public function givenWeeksStartOn(Day $day): self
    {
        $this->weekStartDay = $day;

        return $this;
    }

    public function format(): string
    {
        $parts = [
            "FREQ={$this->frequency->value}",
        ];

        if ($this->interval !== 1) {
            $parts[] = "INTERVAL={$this->interval}";
        }

        if ($this->until !== null) {
            $parts[] = "UNTIL={$this->until->setTimezone(new DateTimeZone('UTC'))->format('Ymd\THis\Z')}";
        }

        if ($this->times !== null) {
            $parts[] = "COUNT={$this->times}";
        }

        if ($this->onSeconds !== []) {
            $parts[] = 'BYSECOND=' . implode(',', $this->onSeconds);
        }

        if ($this->onMinutes !== []) {
            $parts[] = 'BYMINUTE=' . implode(',', $this->onMinutes);
        }

        if ($this->onHours !== []) {
            $parts[] = 'BYHOUR=' . implode(',', $this->onHours);
        }

        if ($this->onDays !== []) {
            $parts[] = 'BYDAY=' . implode(',', array_map(fn (Day $day): string => $day->value, $this->onDays));
        }

        if ($this->onMonthDays !== []) {
            $parts[] = 'BYMONTHDAY=' . implode(',', $this->onMonthDays);
        }

        if ($this->onYearDays !== []) {
            $parts[] = 'BYYEARDAY=' . implode(',', $this->onYearDays);
        }

        if ($this->onWeeks !== []) {
            $parts[] = 'BYWEEKNO=' . implode(',', $this->onWeeks);
        }

        if ($this->onMonths !== []) {
            $parts[] = 'BYMONTH=' . implode(',', array_map(fn (Month $month): string => (string) $month->value, $this->onMonths));
        }

        if ($this->weekStartDay !== null) {
            $parts[] = "WKST={$this->weekStartDay->value}";
        }

        return implode(';', $parts);
    }

    public function __toString(): string
    {
        return $this->format();
    }
}
