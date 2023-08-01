<?php

namespace App\Entity;

use App\Entity\Commission\Commission;
use App\Entity\User\User;
use App\Repository\Company\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[UniqueEntity(fields: ['tin'], message: 'company.tin.unique')]
class Company
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['admin_user'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\NotBlank(message: 'company.title.not_blank')]
    #[Assert\Length(max: 255, maxMessage: 'company.title.max_length')]
    #[Groups(['admin_user'])]
    private ?string $title = null;

    #[ORM\Column(length: 12, unique: true)]
    #[Assert\Regex(
        pattern: '/^\d{10}(\d{2})?\s?$/',
        message: 'company.tin.regex'
    )]
    #[Groups(['admin_user'])]
    private ?string $tin = null;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: User::class, orphanRemoval: true)]
    private Collection $users;

    #[Groups(['admin_user'])]
    private User $admin;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Commission::class, orphanRemoval: true)]
    private Collection $commissions;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Group::class, orphanRemoval: true)]
    private Collection $groups;

    public function __construct(User $user)
    {
        $this->users = new ArrayCollection([$user]);
        $this->commissions = new ArrayCollection();
        $this->groups = new ArrayCollection();
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

    public function getTin(): ?string
    {
        return $this->tin;
    }

    public function setTin(string $tin): self
    {
        $this->tin = $tin;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAdmins(): Collection
    {
        return $this->users->filter(function (User $user) {
            return in_array('ROLE_ADMIN', $user->getRoles());
        });
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setCompany($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCompany() === $this) {
                $user->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return User
     */
    public function getAdmin(): User
    {
        return $this->getAdmins()->first();
    }

    /**
     * @param User $admin
     */
    public function setAdmin(User $admin): void
    {
        $this->admin = $admin;
    }

    /**
     * @return Collection<int, Commission>
     */
    public function getCommissions(): Collection
    {
        return $this->commissions;
    }

    public function addCommission(Commission $commission): self
    {
        if (!$this->commissions->contains($commission)) {
            $this->commissions->add($commission);
            $commission->setCompany($this);
        }

        return $this;
    }

    public function removeCommission(Commission $commission): self
    {
        if ($this->commissions->removeElement($commission)) {
            // set the owning side to null (unless already changed)
            if ($commission->getCompany() === $this) {
                $commission->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
            $group->setCompany($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->removeElement($group)) {
            // set the owning side to null (unless already changed)
            if ($group->getCompany() === $this) {
                $group->setCompany(null);
            }
        }

        return $this;
    }
}
