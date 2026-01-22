<?php

namespace App\Infrastructure\Output\Persistence\Doctrine\Mapper;

use App\Domain\Contract\Model\Contract;
use App\Infrastructure\Output\Persistence\Doctrine\Entity\ContractEntity;

class ContractPersistenceMapper
{
    public function toEntity(Contract $contract): ContractEntity
    {
        $entity = new ContractEntity();
        $entity->setId($contract->id()->value());
        $entity->setNumber($contract->number());
        $entity->setDate($contract->date());
        $entity->setTotalAmount($contract->totalAmount());
        $entity->setPaymentMethod($contract->paymentMethod());

        return $entity;
    }

    public function toDomain(ContractEntity $entity): Contract
    {
        return new Contract(
            $entity->getId(),
            $entity->getNumber(),
            $entity->getDate(),
            $entity->getTotalAmount(),
            $entity->getPaymentMethod()
        );
    }
}
