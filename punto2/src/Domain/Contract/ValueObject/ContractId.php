<?php

namespace App\Domain\Contract\ValueObject;

use Symfony\Component\Uid\Uuid;

final class ContractId
{
    private string $value;

    public function __construct(?string $value = null)
    {
        $this->value = $value ?? Uuid::v4()->toRfc4122();
    }

    public function value(): string
    {
        return $this->value;
    }
}
