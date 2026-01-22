<?php

namespace App\Domain\Contract\Model;

use App\Domain\Contract\ValueObject\{
    ContractId,
    ContractDate,
    Money,
    Months
};
use App\Domain\Contract\Model\PaymentMethod\PaymentMethodInterface;

final class Contract
{
    private ContractId $id;
    private ContractDate $date;
    private Money $totalAmount;
    private Months $months;
    private PaymentMethodInterface $paymentMethod;

    /** @var Installment[] */
    private array $installments = [];

    public function __construct(
        ContractId $id,
        ContractDate $date,
        Money $totalAmount,
        Months $months,
        PaymentMethodInterface $paymentMethod
    ) {
        $this->id = $id;
        $this->date = $date;
        $this->totalAmount = $totalAmount;
        $this->months = $months;
        $this->paymentMethod = $paymentMethod;
    }

    public function id(): ContractId
    {
        return $this->id;
    }

    public function totalAmount(): Money
    {
        return $this->totalAmount;
    }

    public function months(): Months
    {
        return $this->months;
    }

    public function paymentMethod(): PaymentMethodInterface
    {
        return $this->paymentMethod;
    }

    public function addInstallment(Installment $installment): void
    {
        $this->installments[] = $installment;
    }

    /**
     * @return Installment[]
     */
    public function installments(): array
    {
        return $this->installments;
    }

    public function contractDate(): ContractDate
    {
        return $this->date;
    }
}
