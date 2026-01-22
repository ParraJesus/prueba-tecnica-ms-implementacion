<?php

namespace App\Domain\Contract\Repository;

use App\Domain\Contract\Model\Contract;
use App\Domain\Contract\ValueObject\ContractId;

interface ContractRepositoryInterface
{
    /**
     * guarda un contrato en la base de datos.
     * @param Contract $contract
     */
    public function save(Contract $contract): void;

    /**
     * recupera un contrato por su ID.
     * @param ContractId $contractId
     * @return Contract|null
     */
    public function findById(ContractId $contractId): ?Contract;

    /**
     * recupera todos los contratos.
     * @return Contract[]
     */
    public function findAll(): array;
}
