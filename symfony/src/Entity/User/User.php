<?php

namespace App\Entity\User;

use App\Entity\Company;
use App\Entity\Profile\Profile;
use App\Entity\Question;
use App\Entity\Result;
use App\Entity\User\vo\Permissions;
use App\Entity\User\vo\WorkerCard;
use App\Repository\User\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'user.unique')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user', 'admin_user', 'user_editable'])]
    private ?int $id = null;


    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'user.email.not_blank')]
    #[Assert\Email(message: 'user.email.format')]
    #[Groups(['user', 'admin_user', 'user_editable'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['user', 'admin_user', 'user_editable'])]
    private array $roles = [];


    #[ORM\Column(nullable: true)]
    #[Groups(['account', 'user', 'admin_user', 'report'])]
//    #[Assert\NotBlank(message: 'user.first_name.not_blank')]
    private string|null $firstName = null;

    /**
     * @var string|null The hashed password
     */
    #[ORM\Column(nullable: true)]
//    #[Groups(['user'])]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Result::class, orphanRemoval: true)]
    #[Groups(['account', 'result'])]
    private Collection $results;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Question::class)]
    private Collection $questions;

    private ?WorkerCard $workerCard = null;

    #[ORM\ManyToOne(inversedBy: 'user')]
    private ?Company $company = null;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    #[Groups(['user_editable'])]
    #[Assert\NotBlank(message: 'user.login.not_blank')]
    #[Assert\NotNull(message: 'user.login.not_null')]
    #[Assert\Length(max: 255, maxMessage: 'user.login.length')]
    private ?string $login = null;

    #[ORM\OneToOne(inversedBy: 'user', cascade: ['persist', 'remove'])]
    #[Groups(['user_editable'])]
    private ?Profile $profile = null;

    #[ORM\Column]
    #[Groups(['user_editable'])]
    private ?bool $isActive = true;

    #[Groups(['user_editable'])]
    private ?Permissions $permissions =null;

    /**
     * @return WorkerCard|null
     */
    public function getWorkerCard(): ?WorkerCard
    {
        return $this->workerCard;
    }

    /**
     * @param WorkerCard|null $workerCard
     */
    public function setWorkerCard(?WorkerCard $workerCard): void
    {
        $this->workerCard = $workerCard;
    }

    public function __construct()
    {
        $this->results = new ArrayCollection();
        $this->questions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getAvatarUrl(int $size = null): string
    {
        $string = sprintf("https://robohash.org/set_set3/%s.png",
            mb_strtolower(str_replace(' ', '_', $this->firstName))
        );
        if ($size) {
            $string .= "?size={$size}x{$size}";

        }
        return $string;
    }

    /**
     * @return Collection<int, Result>
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResult(Result $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results->add($result);
            $result->setUser($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->removeElement($result)) {
            // set the owning side to null (unless already changed)
            if ($result->getUser() === $this) {
                $result->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setAuthor($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getAuthor() === $this) {
                $question->setAuthor(null);
            }
        }

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

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Permissions|null
     */
    public function getPermissions(): ?Permissions
    {
        return $this->permissions;
    }

    /**
     * @param Permissions $permissions
     */
    public function setPermissions(Permissions $permissions): void
    {
        $this->permissions = $permissions;
    }

}
