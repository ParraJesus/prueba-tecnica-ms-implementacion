<?php

namespace App\Domain\Contract\ValueObject;

final class Money
{
    private float $amount;

    public function __construct(float $amount)
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException('Amount cannot be negative');
        }

        $this->amount = round($amount, 2);
    }

    public function value(): float
    {
        return $this->amount;
    }

    public function add(Money $other): Money
    {
        return new Money($this->amount + $other->value());
    }

    public function subtract(Money $other): Money
    {
        return new Money(max(0, $this->amount - $other->value()));
    }

    public function multiply(float $factor): Money
    {
        return new Money($this->amount * $factor);
    }
}
