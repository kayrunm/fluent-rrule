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
    private ?DateTimeImmutable $until = null;

    /**
     * @param positive-int $interval
     */
    public function __construct(Frequency $frequency, int $interval = 1)
    {
        $this->frequency = $frequency;
        $this->interval = $interval;
    }

    public static function secondly(): self
    {
        return new self(Frequency::SECONDLY);
    }

    public static function minutely(): self
    {
        return new self(Frequency::MINUTELY);
    }

    public static function hourly(): self
    {
        return new self(Frequency::HOURLY);
    }

    public static function daily(): self
    {
        return new self(Frequency::DAILY);
    }

    public static function weekly(): self
    {
        return new self(Frequency::WEEKLY);
    }

    public static function monthly(): self
    {
        return new self(Frequency::MONTHLY);
    }

    public static function yearly(): self
    {
        return new self(Frequency::YEARLY);
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

    /** @return $this */
    public function until(DateTimeInterface $until): self
    {
        $this->until = DateTimeImmutable::createFromInterface($until);

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

        return implode(';', $parts);
    }
}
