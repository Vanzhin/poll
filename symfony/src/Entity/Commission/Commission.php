<?php

namespace App\Entity\Commission;

use App\Entity\Company;
use App\Entity\Protocol\Protocol;
use App\Entity\User\User;
use App\Repository\Commission\CommissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CommissionRepository::class)]
class Commission
{
    public const MIN_PARTICIPANTS = 3;
    public const MAX_PARTICIPANTS = 10;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['admin', 'admin_commission', 'admin_protocol'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: 'commission.title.not_null')]
    #[Assert\NotBlank(message: 'commission.title.not_blank')]
    #[Assert\Length(max: 255, maxMessage: 'commission.title.max_length')]
    #[Groups(['admin_commission'])]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'commissions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'commissions')]
    #[Assert\Count(min: self::MIN_PARTICIPANTS, max: self::MAX_PARTICIPANTS,
        minMessage: 'commission.participant.min', maxMessage: 'commission.participant.min')]
    #[Groups(['admin_commission'])]
    private Collection $participant;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'commission.head.not_null')]
    #[Groups(['admin_commission'])]
    private ?User $head = null;

    #[ORM\OneToMany(mappedBy: 'commission', targetEntity: Protocol::class)]
    private Collection $protocols;

    public function __construct()
    {
        $this->participant = new ArrayCollection();
        $this->protocols = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipant(): Collection
    {
        return $this->participant;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participant->contains($participant)) {
            $this->participant->add($participant);
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        $this->participant->removeElement($participant);

        return $this;
    }

    public function removeAllParticipants(): self
    {
        $this->participant->clear();

        return $this;
    }

    public function getHead(): ?User
    {
        return $this->head;
    }

    public function setHead(?User $head): self
    {
        $this->head = $head;

        return $this;
    }

    /**
     * @return Collection<int, Protocol>
     */
    public function getProtocols(): Collection
    {
        return $this->protocols;
    }

    public function addProtocol(Protocol $protocol): self
    {
        if (!$this->protocols->contains($protocol)) {
            $this->protocols->add($protocol);
            $protocol->setCommission($this);
        }

        return $this;
    }

    public function removeProtocol(Protocol $protocol): self
    {
        if ($this->protocols->removeElement($protocol)) {
            // set the owning side to null (unless already changed)
            if ($protocol->getCommission() === $this) {
                $protocol->setCommission(null);
            }
        }

        return $this;
    }
}