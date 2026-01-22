<?php

namespace App\Domain\Contract\ValueObject;

final class ContractDate
{
    private \DateTimeImmutable $date;

    public function __construct(\DateTimeImmutable $date)
    {
        $this->date = $date;
    }

    public function value(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function addMonths(int $months): \DateTimeImmutable
    {
        return $this->date->modify("+{$months} month");
    }
}
