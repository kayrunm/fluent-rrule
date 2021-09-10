<?php

declare(strict_types=1);

namespace Kayrunm\FluentRrule;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

class Schedule
{
    private Frequency $frequency;
    private ?DateTimeImmutable $until = null;

    public function __construct(Frequency $frequency)
    {
        $this->frequency = $frequency;
    }

    /** @return $this */
    public function until(DateTimeInterface $until): static
    {
        $this->until = DateTimeImmutable::createFromInterface($until);

        return $this;
    }

    public function format(): string
    {
        $parts = ["FREQ={$this->frequency->value}"];

        if ($this->until !== null) {
            $parts[] = "UNTIL={$this->until->setTimezone(new DateTimeZone('UTC'))->format('Ymd\THis\Z')}";
        }

        return implode(';', $parts);
    }
}
