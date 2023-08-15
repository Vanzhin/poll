<?php

namespace App\Entity;

use App\Entity\Commission\Commission;
use App\Repository\ProtocolRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ProtocolRepository::class)]
class Protocol
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['admin_protocol'])]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    #[Groups(['admin_protocol'])]
    #[Assert\NotNull(message: 'protocol.order_number.not_null')]
    #[Assert\NotBlank(message: 'protocol.order_number.not_blank')]
    #[Assert\Length(max: 10, maxMessage: 'protocol.order_number.max')]
    private ?string $orderNumber = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Groups(['admin_protocol'])]
    #[Assert\NotNull(message: 'protocol.date.not_null')]
    private ?\DateTimeInterface $orderDate = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['admin_protocol'])]
    #[Assert\NotNull(message: 'protocol.check_reason.not_null')]
    #[Assert\NotBlank(message: 'protocol.check_reason.not_blank')]
    private ?string $checkReason = null;

    #[ORM\ManyToOne(inversedBy: 'protocols')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['admin_protocol'])]
    #[Assert\NotNull(message: 'protocol.commission.not_null')]
    private ?Commission $commission = null;

    #[ORM\Column(length: 25)]
    #[Assert\NotNull(message: 'protocol.number.not_null')]
    #[Assert\NotBlank(message: 'protocol.number.not_blank')]
    #[Assert\Length(max: 25, maxMessage: 'protocol.number.max')]
    private ?string $number = null;

    #[ORM\OneToOne(inversedBy: 'protocol', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
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

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getGroups(): ?Group
    {
        return $this->groups;
    }

    public function setGroups(Group $groups): self
    {
        $this->groups = $groups;

        return $this;
    }
}
