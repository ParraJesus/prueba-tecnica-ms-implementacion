<?php

namespace App\Application\Contract\UseCase;

use App\Domain\Contract\Model\Contract;
use App\Domain\Contract\Repository\ContractRepositoryInterface;
use App\Domain\Contract\ValueObject\Money;
use App\Domain\Contract\Model\PaymentMethod\PaymentMethodInterface;

class CreateContractUseCase
{
    private ContractRepositoryInterface $contractRepository;

    public function __construct(ContractRepositoryInterface $contractRepository)
    {
        $this->contractRepository = $contractRepository;
    }

    public function handle(CreateContractCommand $command): Contract
    {
        // Crear la fecha del contrato
        $date = new \DateTimeImmutable($command->date);

        // Crear los objetos de valor
        $totalAmount = new Money($command->totalAmount);
        $months = new Months($command->months);

        // Aquí es donde se decide qué PaymentMethod usar basado en la entrada
        $paymentMethod = $this->getPaymentMethod($command->paymentMethod);

        // Crear el contrato
        $contract = new Contract(
            new ContractId(),
            new ContractDate($date),
            $totalAmount,
            $months,
            $paymentMethod
        );

        // Calcular las cuotas
        $this->calculateInstallments($contract);

        // Guardar el contrato en la base de datos
        $this->contractRepository->save($contract);

        return $contract;
    }

    private function getPaymentMethod(string $paymentMethodType): PaymentMethodInterface
    {
        // Dependiendo del tipo de pago, devolver la clase correcta
        if ($paymentMethodType === 'PayPal') {
            return new PayPalPaymentMethod();
        } elseif ($paymentMethodType === 'PayOnline') {
            return new PayOnlinePaymentMethod();
        }

        throw new \InvalidArgumentException('Invalid payment method');
    }

    private function calculateInstallments(Contract $contract): void
    {
        // Calcular las cuotas según la cantidad de meses
        $totalAmount = $contract->totalAmount();
        $months = $contract->months()->value();

        // El saldo restante (en este caso es el totalAmount) se distribuye en los meses
        $remainingBalance = $totalAmount;
        for ($i = 1; $i <= $months; $i++) {
            // Llamar al método de pago para calcular la cuota
            $installmentAmount = $contract->paymentMethod()->calculateInstallment($remainingBalance);

            // Crear la cuota y agregarla al contrato
            $installment = new Installment(
                $i,  // Número de cuota
                (new \DateTimeImmutable())->modify("+{$i} month"), // Fecha de vencimiento (por ejemplo, mes a mes)
                $installmentAmount
            );

            $contract->addInstallment($installment);
        }
    }
}
