<?php

namespace App\Entity;

use App\Entity\User\User;
use App\Repository\ProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'profile.profile.first_name.not_blank')]
    #[Assert\NotNull(message: 'profile.profile.first_name.not_blank')]
    #[Assert\LessThanOrEqual(100)]
    private ?string $firstName = null;

    #[Assert\NotBlank(message: 'profile.profile.middle_name.not_blank')]
    #[Assert\NotNull(message: 'profile.profile.middle_name.not_blank')]
    #[Assert\LessThanOrEqual(100)]
    #[ORM\Column(length: 100)]
    private ?string $middleName = null;

    #[Assert\NotBlank(message: 'profile.profile.last_name.not_blank')]
    #[Assert\NotNull(message: 'profile.profile.last_name.not_blank')]
    #[Assert\LessThanOrEqual(150)]
    #[ORM\Column(length: 150)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Assert\LessThanOrEqual(255)]
    private ?string $position = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\LessThanOrEqual(255)]
    private ?string $department = null;

    #[ORM\Column(length: 15, nullable: true)]
    #[Assert\Regex(pattern: '/^\d{3}-\d{3}-\d{3} \d{2}$/', message: 'profile.snils.format')]
    private ?string $snils = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\LessThanOrEqual(255)]
    private ?string $diploma = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\LessThanOrEqual(255)]
    private ?string $citizenship = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Choice([
        'среднее профессиональное образование',
        'высшее образование - бакалавриат',
        'высшее образование - специалитет, магистратура',
        'высшее образование - подготовка кадров высшей квалификации'
    ],
        message: 'profile.education.choice')]
    private ?string $educationLevel = null;

    #[ORM\OneToOne(mappedBy: 'profile', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function setMiddleName(string $middleName): self
    {
        $this->middleName = $middleName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getSnils(): ?string
    {
        return $this->snils;
    }

    public function setSnils(?string $snils): self
    {
        $this->snils = $snils;

        return $this;
    }

    public function getDiploma(): ?string
    {
        return $this->diploma;
    }

    public function setDiploma(?string $diploma): self
    {
        $this->diploma = $diploma;

        return $this;
    }

    public function getCitizenship(): ?string
    {
        return $this->citizenship;
    }

    public function setCitizenship(?string $citizenship): self
    {
        $this->citizenship = $citizenship;

        return $this;
    }

    public function getEducationLevel(): ?string
    {
        return $this->educationLevel;
    }

    public function setEducationLevel(?string $educationLevel): self
    {
        $this->educationLevel = $educationLevel;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setProfile(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getProfile() !== $this) {
            $user->setProfile($this);
        }

        $this->user = $user;

        return $this;
    }
}