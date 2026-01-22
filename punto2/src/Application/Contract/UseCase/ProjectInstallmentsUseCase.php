<?php

namespace App\Application\Contract\UseCase;

use App\Application\Contract\DTO\InstallmentResult;
use App\Application\Contract\DTO\ProjectInstallmentsQuery;
use App\Domain\Contract\Repository\ContractRepositoryInterface;

class ProjectInstallmentsUseCase
{
    private ContractRepositoryInterface $repository;

    public function __construct(ContractRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(ProjectInstallmentsQuery $query): array
    {
        $contract = $this->repository->findById(new \App\Domain\Contract\ValueObject\ContractId($query->contractId));

        if ($contract === null) {
            throw new \InvalidArgumentException("Contract not found.");
        }

        //proyectar las cuotas
        $installments = $this->projectInstallments($contract);

        //mapear los resultados a DTOs
        return array_map(function ($installment) {
            return new InstallmentResult(
                $installment->number(),
                $installment->dueDate()->format('Y-m-d'),
                $installment->amount()->value()
            );
        }, $installments);
    }

    private function projectInstallments(\App\Domain\Contract\Model\Contract $contract): array
    {
        $installments = [];
        $remainingBalance = $contract->totalAmount();

        for ($i = 1; $i <= $contract->months()->value(); $i++) {
            $dueDate = $contract->contractDate()->addMonths($i);
            $installmentAmount = $contract->paymentMethod()->calculateInstallment($remainingBalance);

            //crear la cuota y agregarla a la lista
            $installments[] = new \App\Domain\Contract\Model\Installment($i, $dueDate, $installmentAmount);
            $remainingBalance = $remainingBalance->subtract($installmentAmount); //reducir el saldo pendiente
        }

        return $installments;
    }
}
