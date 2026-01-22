<?php

namespace App\Domain\Contract\Model\PaymentMethod;

use App\Domain\Contract\ValueObject\Money;

interface PaymentMethodInterface
{
    /**
     * Calcula el valor de la cuota a pagar.
     * @param Money $remainingBalance El saldo restante a pagar
     * @return Money El valor de la cuota
     */
    public function calculateInstallment(Money $remainingBalance): Money;
}
