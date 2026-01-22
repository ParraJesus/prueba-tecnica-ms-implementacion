<?php

namespace App\Application\Contract\DTO;

class ProjectInstallmentsQuery
{
    public string $contractId;

    public function __construct(string $contractId)
    {
        $this->contractId = $contractId;
    }
}
