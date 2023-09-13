<?php

namespace App\Entity\Protocol;

use App\Entity\Commission\Commission;
use App\Entity\Group;
use App\Entity\Test;
use App\Entity\User\User;
use App\Repository\Protocol\ProtocolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ProtocolRepository::class)]
//#[UniqueEntity(fields: ['groups', 'test'], message: 'protocol.test.unique')]
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
    #[Groups(['admin_protocol'])]
    private ?string $number = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'protocol')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['admin_protocol'])]
    #[Assert\NotNull(message: 'protocol.groups.not_null')]
    private ?Group $groups = null;

    #[ORM\ManyToOne(inversedBy: 'protocols')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'protocol.test.not_null')]
    #[Groups(['admin_protocol'])]
    private ?Test $test = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['admin_protocol'])]
    private ?string $file = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['admin_protocol'])]
    private ?string $reg_number = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'protocols')]
    #[Assert\NotNull(message: 'protocol.user.not_null')]
    #[Groups(['admin_protocol'])]
    private Collection $user;

    #[ORM\Column(length: 255)]
    private ?string $template = null;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

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

    public function getTest(): ?Test
    {
        return $this->test;
    }

    public function setTest(?Test $test): self
    {
        $this->test = $test;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getRegNumber(): ?string
    {
        return $this->reg_number;
    }

    public function setRegNumber(?string $reg_number): self
    {
        $this->reg_number = $reg_number;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(string $template): self
    {
        $this->template = $template;

        return $this;
    }
}
