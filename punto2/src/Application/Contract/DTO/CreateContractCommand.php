<?php

namespace App\Application\Contract\DTO;

use App\Domain\Contract\ValueObject\Money;
use App\Domain\Contract\ValueObject\ContractDate;
use App\Domain\Contract\ValueObject\Months;


class CreateContractCommand
{
    public string $number;
    public string $date;
    public float $totalAmount;
    public string $paymentMethod;
    public int $months;

    public function __construct(
        string $number,
        string $date,
        float $totalAmount,
        string $paymentMethod,
        int $months
    ) {
        $this->number = $number;
        $this->date = $date;
        $this->totalAmount = $totalAmount;
        $this->paymentMethod = $paymentMethod;
        $this->months = $months;
    }

    public function toDomain(): \App\Domain\Contract\Model\Contract
    {
        //mapear este DTO al dominio
        return new \App\Domain\Contract\Model\Contract(
            new \App\Domain\Contract\ValueObject\ContractId(), // Creamos un nuevo ID (UUID)
            new ContractDate(new \DateTimeImmutable($this->date)), // Creamos un ContractDate
            new Money($this->totalAmount),  // Creamos el objeto Money
            new Months($this->months),  // Creamos el objeto Months
            $this->paymentMethod === 'paypal' 
                ? new \App\Domain\Contract\Model\PaymentMethod\PayPalPaymentMethod() 
                : new \App\Domain\Contract\Model\PaymentMethod\PayOnlinePaymentMethod()
        );
    }
}