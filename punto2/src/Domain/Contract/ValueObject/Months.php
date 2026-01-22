<?php

namespace App\Domain\Contract\ValueObject;

final class Months
{
    private int $value;

    public function __construct(int $value)
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException('Months must be greater than zero');
        }

        $this->value = $value;
    }

    public function value(): int
    {
        return $this->value;
    }
}
