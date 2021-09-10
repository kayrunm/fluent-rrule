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
        return self::everyHour();
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
    public function everyNthHour(int $n): self
    {
        return new self(Frequency::HOURLY, $n);
    }

    /**
     * @param positive-int $n
     */
    public function everyNthDay(int $n): self
    {
        return new self(Frequency::DAILY, $n);
    }

    /**
     * @param positive-int $n
     */
    public function everyNthWeek(int $n): self
    {
        return new self(Frequency::WEEKLY, $n);
    }

    /**
     * @param positive-int $n
     */
    public function everyNthMonth(int $n): self
    {
        return new self(Frequency::MONTHLY, $n);
    }

    /**
     * @param positive-int $n
     */
    public function everyNthYear(int $n): self
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
    public function from(DateTimeInterface $from): self
    {
        $this->from = DateTimeImmutable::createFromInterface($from);

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

    public function format(): string
    {
        $parts = [
            "FREQ={$this->frequency->value}",
            "INTERVAL={$this->interval}", // todo should we leave this out if it is 1 (default)?
        ];

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

        return implode(';', $parts);
    }
}
