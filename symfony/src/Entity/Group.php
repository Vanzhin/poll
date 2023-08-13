<?php

namespace App\Entity;

use App\Entity\User\User;
use App\Repository\Group\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['admin_group'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['admin_group'])]
    #[Assert\NotNull(message: 'group.title.not_null')]
    #[Assert\NotBlank(message: 'group.title.not_blank')]
    #[Assert\Length(max: 255, maxMessage: 'group.title.length')]
    private ?string $title = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'groups')]
    #[Groups(['admin_group'])]
    #[Assert\Count(min: 1, minMessage: 'group.participants.min')]
    private Collection $participants;

    #[ORM\ManyToMany(targetEntity: Test::class, inversedBy: 'groups')]
    #[Groups(['admin_group'])]
    #[Assert\Count(min: 1, minMessage: 'group.test.min')]
    private Collection $availableTests;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['admin_group'])]
    #[Assert\NotNull(message: 'group.date.not_null')]
//    #[Assert\DateTime('Y-m-d H:i:s', message: 'group.date.format')]
    private ?\DateTimeImmutable $started_at = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['admin_group'])]
    #[Assert\NotNull(message: 'group.date.not_null')]
//    #[Assert\DateTime('Y-m-d H:i:s', message: 'group.date.format')]
    private ?\DateTimeImmutable $finished_at = null;

    #[ORM\ManyToOne(inversedBy: 'groups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['admin_group'])]
    private ?User $owner = null;

    #[ORM\OneToOne(inversedBy: 'groups', cascade: ['persist', 'remove'])]
    private ?Protocol $protocol = null;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->availableTests = new ArrayCollection();
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

    /**
     * @return Collection<int, User>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    public function removeAllParticipants(): self
    {
        $this->participants->clear();
        return $this;
    }

    /**
     * @return Collection<int, Test>
     */
    public function getAvailableTests(): Collection
    {
        return $this->availableTests;
    }

    public function addAvailableTest(Test $availableTest): self
    {
        if (!$this->availableTests->contains($availableTest)) {
            $this->availableTests->add($availableTest);
        }

        return $this;
    }

    public function removeAllAvailableTest(): self
    {
        $this->availableTests->clear();
        return $this;
    }

    public function removeAvailableTest(Test $availableTest): self
    {
        $this->availableTests->removeElement($availableTest);

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->started_at;
    }

    public function setStartedAt(\DateTimeImmutable $started_at): self
    {
        $this->started_at = $started_at;

        return $this;
    }

    public function getFinishedAt(): ?\DateTimeImmutable
    {
        return $this->finished_at;
    }

    public function setFinishedAt(\DateTimeImmutable $finished_at): self
    {
        $this->finished_at = $finished_at;

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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getProtocol(): ?Protocol
    {
        return $this->protocol;
    }

    public function setProtocol(?Protocol $protocol): self
    {
        $this->protocol = $protocol;

        return $this;
    }
}
