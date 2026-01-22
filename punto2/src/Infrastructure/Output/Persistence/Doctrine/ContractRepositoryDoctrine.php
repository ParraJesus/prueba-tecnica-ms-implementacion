<?php

namespace App\Infrastructure\Output\Persistence\Doctrine;

use App\Domain\Contract\Model\Contract;
use App\Domain\Contract\ValueObject\ContractId;
use App\Domain\Contract\Repository\ContractRepositoryInterface;
use App\Infrastructure\Output\Persistence\Doctrine\Entity\ContractEntity;
use App\Infrastructure\Output\Persistence\Doctrine\Mapper\ContractPersistenceMapper;
use Doctrine\ORM\EntityManagerInterface;

class ContractRepositoryDoctrine implements ContractRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ContractPersistenceMapper $mapper
    ) {}

    public function save(Contract $contract): void
    {
        $this->entityManager->persist($contract);
        $this->entityManager->flush();
    }

    public function findById(ContractId $contractId): ?Contract
{
    $entity = $this->entityManager->find(ContractEntity::class, $contractId->value());

    return $entity ? $this->mapper->toDomain($entity) : null;
}

    public function findAll(): array
    {
        $entities = $this->entityManager->getRepository(ContractEntity::class)->findAll();
        return array_map([$this->mapper, 'toDomain'], $entities);
    }
}
