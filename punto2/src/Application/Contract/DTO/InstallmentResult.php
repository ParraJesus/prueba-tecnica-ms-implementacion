<?php

namespace App\Application\Contract\DTO;

class InstallmentResult
{
    public int $number;
    public string $dueDate;
    public float $amount;

    public function __construct(int $number, string $dueDate, float $amount)
    {
        $this->number = $number;
        $this->dueDate = $dueDate;
        $this->amount = $amount;
    }
}
