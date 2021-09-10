<?php

declare(strict_types=1);

namespace Kayrunm\FluentRrule;

use DateTimeInterface;
use InvalidArgumentException;

class Schedule
{
    private Frequency $frequency;
    private ?DateTimeInterface $until = null;

    public function __construct(Frequency $frequency)
    {
        $this->frequency = $frequency;
    }

    /** @return $this */
    public function until(DateTimeInterface $until): static
    {
        // todo: handle non-UTC times
        if ($until->getTimezone()->getName() !== 'UTC') {
            throw new InvalidArgumentException('Must be UTC for now');
        }

        $this->until = $until;

        return $this;
    }

    public function format(): string
    {
        $parts = ["FREQ={$this->frequency->format()}"];

        if ($this->until !== null) {
            $parts[] = "UNTIL={$this->until->format('Ymd\THis\Z')}";
        }

        return implode(';', $parts);
    }
}
