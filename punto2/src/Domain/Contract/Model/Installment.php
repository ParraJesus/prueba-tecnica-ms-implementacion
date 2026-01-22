<?php

namespace App\Domain\Contract\Model;

use App\Domain\Contract\ValueObject\Money;

final class Installment
{
    private int $number;
    private \DateTimeImmutable $dueDate;
    private Money $amount;

    public function __construct(
        int $number,
        \DateTimeImmutable $dueDate,
        Money $amount
    ) {
        $this->number = $number;
        $this->dueDate = $dueDate;
        $this->amount = $amount;
    }

    public function number(): int
    {
        return $this->number;
    }

    public function dueDate(): \DateTimeImmutable
    {
        return $this->dueDate;
    }

    public function amount(): Money
    {
        return $this->amount;
    }
}
