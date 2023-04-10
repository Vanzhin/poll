<?php

namespace App\Entity;

use App\Repository\OrganizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


//#[ORM\Entity(repositoryClass: OrganizationRepository::class)]
class Organization
{
//    #[ORM\Id]
//    #[ORM\GeneratedValue]
//    #[ORM\Column]
    private ?int $id = null;

//    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'organization.title.not_blank')]
    private ?string $title = null;

//    #[ORM\Column]
    #[Assert\Regex(pattern:'/^\d{10}$/', message: 'organization.inn.format')]
    #[Assert\NotBlank(message: 'organization.inn.not_blank')]
    private ?int $inn = null;

//    #[ORM\OneToMany(mappedBy: 'organization', targetEntity: User::class)]
    private Collection $users;


    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    public function getInn(): ?int
    {
        return $this->inn;
    }

    public function setInn(int $inn): self
    {
        $this->inn = $inn;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setOrganization($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getOrganization() === $this) {
                $user->setOrganization(null);
            }
        }

        return $this;
    }
}
