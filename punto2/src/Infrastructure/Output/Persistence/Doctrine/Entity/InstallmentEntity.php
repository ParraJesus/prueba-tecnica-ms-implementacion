<?php
namespace App\Infrastructure\Output\Persistence\Doctrine\Entity;

use App\Domain\Contract\Model\Installment;
use App\Domain\Contract\ValueObject\Money;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'installments')]
class InstallmentEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'float')]
    private float $amount;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $dueDate;

    #[ORM\ManyToOne(targetEntity: ContractEntity::class, inversedBy: 'installments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ContractEntity $contract = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getDueDate(): \DateTimeInterface
    {
        return $this->dueDate;
    }

    public function getContract(): ?ContractEntity
    {
        return $this->contract;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function setDueDate(\DateTimeInterface $dueDate): self
    {
        $this->dueDate = $dueDate;
        return $this;
    }

    public function setContract(?ContractEntity $contract): self
    {
        $this->contract = $contract;
        return $this;
    }

    public function toDomain(): Installment
    {
        return new Installment(
            $this->getId(),
            new \DateTimeImmutable($this->getDueDate()->format('Y-m-d H:i:s')),
            new Money($this->getAmount())
        );
    }

    public function fromDomain(Installment $installment): self
    {
        $this->amount = $installment->amount()->value();
        $this->dueDate = $installment->dueDate();
        return $this;
    }
}
