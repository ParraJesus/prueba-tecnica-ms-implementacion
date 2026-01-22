<?php

namespace App\Infrastructure\Input\Http\DTO;

use App\Domain\Contract\ValueObject\{
    ContractDate,
    Money,
    Months
};
use Symfony\Component\HttpFoundation\Request;

class CreateContractRequest
{
    public function __construct(
        public string $number,
        public string $date,      // Recibimos como string y lo convertimos en un objeto ContractDate
        public float $totalAmount,
        public string $paymentMethod,  // Método de pago sigue siendo un string
        public int $months          // Recibimos los meses como entero
    ) {}

    public static function fromRequest(Request $request): self
    {
        $data = json_decode($request->getContent(), true);

        return new self(
            $data['number'],
            $data['date'],  // Este es un string que convertiremos a ContractDate
            $data['totalAmount'],
            $data['paymentMethod'],
            $data['months']  // Los meses se pasan como entero
        );
    }

    public function toDomain(): \App\Domain\Contract\Model\Contract
    {
        // Convertimos los datos a los Value Objects adecuados
        return new \App\Domain\Contract\Model\Contract(
            new \App\Domain\Contract\ValueObject\ContractId(),  // Creamos un nuevo ID (UUID)
            new ContractDate(new \DateTimeImmutable($this->date)),  // Creamos un ContractDate
            new Money($this->totalAmount),  // Creamos el objeto Money
            new Months($this->months),  // Creamos el objeto Months
            $this->createPaymentMethod($this->paymentMethod)  // Creamos el objeto de pago adecuado
        );
    }

    private function createPaymentMethod(string $paymentMethod): \App\Domain\Contract\Model\PaymentMethod\PaymentMethodInterface
    {
        // Aquí convertimos el string 'PayPal' o 'PayOnline' en la clase correspondiente
        if ($paymentMethod === 'PayPal') {
            return new \App\Domain\Contract\Model\PaymentMethod\PayPalPaymentMethod();
        }

        if ($paymentMethod === 'PayOnline') {
            return new \App\Domain\Contract\Model\PaymentMethod\PayOnlinePaymentMethod();
        }

        // Si el método de pago es inválido, lanzamos una excepción
        throw new \InvalidArgumentException('Invalid payment method');
    }
}
