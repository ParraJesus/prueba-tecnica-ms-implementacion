<?php
namespace App\Infrastructure\Output\Persistence\Doctrine\Entity;

use App\Domain\Contract\Model\Contract;
use App\Domain\Contract\Model\Installment;
use App\Domain\Contract\ValueObject\ContractId;
use App\Domain\Contract\ValueObject\ContractDate;
use App\Domain\Contract\ValueObject\Money;
use App\Domain\Contract\ValueObject\Months;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'contracts')]
class ContractEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 255)]
    private string $id;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $date;

    #[ORM\Column(type: 'float')]
    private float $totalAmount;

    #[ORM\Column(type: 'integer')]
    private int $months;

    #[ORM\Column(type: 'string', length: 255)]
    private string $paymentMethod;

    #[ORM\OneToMany(mappedBy: 'contract', targetEntity: InstallmentEntity::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $installments;

    public function __construct()
    {
        $this->installments = new ArrayCollection();
    }

    // ===================== Métodos de acceso =====================
    public function getId(): string
    {
        return $this->id;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    public function getMonths(): int
    {
        return $this->months;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function getInstallments(): Collection
    {
        return $this->installments;
    }

    public function addInstallment(InstallmentEntity $installment): self
    {
        if (!$this->installments->contains($installment)) {
            $this->installments[] = $installment;
            $installment->setContract($this);
        }
        return $this;
    }

    public function removeInstallment(InstallmentEntity $installment): self
    {
        if ($this->installments->removeElement($installment)) {
            if ($installment->getContract() === $this) {
                $installment->setContract(null);
            }
        }
        return $this;
    }

    // ===================== Métodos de dominio =====================
    public function toDomain(): Contract
    {
        return new Contract(
            new ContractId($this->getId()),
            new ContractDate($this->getDate()),
            new Money($this->getTotalAmount()),
            new Months($this->getMonths()),
            $this->getPaymentMethod() // Suponiendo que PaymentMethod es una interfaz con implementación
        );
    }

    public function fromDomain(Contract $contract): self
    {
        $this->id = $contract->id()->value();
        $this->date = $contract->contractDate()->value();
        $this->totalAmount = $contract->totalAmount()->value();
        $this->months = $contract->months()->value();
        $this->paymentMethod = $contract->paymentMethod()->getName();

        return $this;
    }
}
