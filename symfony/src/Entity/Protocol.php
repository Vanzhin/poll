<?php

namespace App\Entity;

use App\Entity\Commission\Commission;
use App\Repository\ProtocolRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProtocolRepository::class)]
class Protocol
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $orderNumber = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $orderDate = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $checkReason = null;

    #[ORM\ManyToOne(inversedBy: 'protocols')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commission $commission = null;

    #[ORM\OneToOne(mappedBy: 'protocol', cascade: ['persist', 'remove'])]
    private ?Group $groups = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(string $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeInterface $orderDate): self
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getCheckReason(): ?string
    {
        return $this->checkReason;
    }

    public function setCheckReason(string $checkReason): self
    {
        $this->checkReason = $checkReason;

        return $this;
    }

    public function getCommission(): ?Commission
    {
        return $this->commission;
    }

    public function setCommission(?Commission $commission): self
    {
        $this->commission = $commission;

        return $this;
    }

    public function getGroups(): ?Group
    {
        return $this->groups;
    }

    public function setGroups(?Group $groups): self
    {
        // unset the owning side of the relation if necessary
        if ($groups === null && $this->groups !== null) {
            $this->groups->setProtocol(null);
        }

        // set the owning side of the relation if necessary
        if ($groups !== null && $groups->getProtocol() !== $this) {
            $groups->setProtocol($this);
        }

        $this->groups = $groups;

        return $this;
    }
}
