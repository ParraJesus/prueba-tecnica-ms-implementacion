<?php

namespace App\Domain\Contract\Model\PaymentMethod;

use App\Domain\Contract\ValueObject\Money;

class PayOnlinePaymentMethod implements PaymentMethodInterface
{
    /**
     * calcula la cuota a pagar para PayOnline.
     * @param Money $remainingBalance El saldo restante
     * @return Money El valor de la cuota
     */
    public function calculateInstallment(Money $remainingBalance): Money
    {
        //interés de 2% y la tarifa de 1%
        $interest = $remainingBalance->multiply(0.02); // 2% de interés
        $fee = $remainingBalance->multiply(0.01); // 1% de tarifa

        //la cuota es la suma de saldo + interés + tarifa
        return $remainingBalance->add($interest)->add($fee);
    }
}
