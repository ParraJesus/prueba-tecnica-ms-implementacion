<?php

namespace App\Application\Contract\Service;

use App\Domain\Contract\Model\Contract;
use App\Domain\Contract\Model\Installment;
use App\Domain\Contract\ValueObject\Money;

class InstallmentCalculator
{
    /**
     * Calcula las cuotas para un contrato.
     *
     * @param Contract $contract
     * @return Installment[]
     */
    public function calculateInstallments(Contract $contract): array
    {
        $installments = [];
        $remainingBalance = $contract->totalAmount(); // Total a pagar
        $paymentMethod = $contract->paymentMethod();
        $months = $contract->months()->value();
        $contractDate = $contract->contractDate()->value();

        for ($i = 1; $i <= $months; $i++) {
            //la fecha de pago de cada cuota
            $dueDate = $contractDate->add(new \DateInterval('P' . $i . 'M'));

            //calcular el monto de la cuota
            $installmentAmount = $paymentMethod->calculateInstallment($remainingBalance);

            //crear la cuota y la agregamos al arreglo
            $installments[] = new Installment($i, $dueDate, $installmentAmount);

            //reducir el saldo pendiente
            $remainingBalance = $remainingBalance->subtract($installmentAmount);
        }

        return $installments;
    }
}
