<?php

namespace App\Entity\Profile;

use App\Entity\User\User;
use App\Repository\ProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{
    public static array $choices = [
        'educationLevel' => self::EDUCATION_LEVEL
    ];

    public const EDUCATION_LEVEL = [
        'среднее профессиональное образование',
        'высшее образование - бакалавриат',
        'высшее образование - специалитет, магистратура',
        'высшее образование - подготовка кадров высшей квалификации'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'profile.first_name.not_blank')]
    #[Assert\NotNull(message: 'profile.first_name.not_null')]
    #[Assert\Length(max: 100, maxMessage: 'profile.first_name.length')]
    #[Groups(['user_editable', 'admin_commission'])]
    private ?string $firstName = null;

    #[Assert\NotBlank(message: 'profile.middle_name.not_blank')]
    #[Assert\NotNull(message: 'profile.middle_name.not_null')]
    #[Assert\Length(max: 100, maxMessage: 'profile.middle_name.length')]
    #[ORM\Column(length: 100)]
    #[Groups(['user_editable', 'admin_commission'])]
    private ?string $middleName = null;

    #[Assert\NotBlank(message: 'profile.last_name.not_blank')]
    #[Assert\NotNull(message: 'profile.last_name.not_null')]
    #[Assert\Length(max: 150, maxMessage: 'profile.last_name.length')]
    #[ORM\Column(length: 150)]
    #[Groups(['user_editable', 'admin_commission'])]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255, maxMessage: 'profile.position.length')]
    #[Assert\NotBlank(message: 'profile.position.not_blank')]
    #[Assert\NotNull(message: 'profile.position.not_null')]
    #[Groups(['user_editable'])]
    private ?string $position = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: 'profile.department.length')]
    #[Groups(['user_editable'])]
    private ?string $department = null;

    #[ORM\Column(length: 15, nullable: true)]
    #[Assert\Regex(pattern: '/^\d{3}-\d{3}-\d{3} \d{2}$/', message: 'profile.snils.format')]
    #[Groups(['user_editable'])]
    private ?string $snils = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: 'profile.diploma.length')]
    #[Groups(['user_editable'])]
    private ?string $diploma = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: 'profile.citizenship.length')]
    #[Groups(['user_editable'])]
    private ?string $citizenship = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Choice(
        choices: self::EDUCATION_LEVEL,
        message: 'profile.education.choice')]
    #[Groups(['user_editable'])]
    private ?string $educationLevel = null;

    #[ORM\OneToOne(mappedBy: 'profile', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Email(message: 'user.email.format')]
    private ?string $email = null;

    #[ORM\Column(length: 11, nullable: true)]
    private ?string $phone = null;

    private array $notification = [];

    private array $notificationType = [];

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getNotification(): array
    {
        return [
            "email" => $this->email,
            "phone" => $this->phone
        ];
    }

    public function getNotificationType(): array
    {
        $types = [];
        if ($this->email) {
            $types[] = 'email';
        }
        if ($this->phone) {
            $types[] = 'phone';
        }
        return $types;
    }

    public function getShortName(): string
    {
        $shortName = $this->getLastName() . ' ' .
            mb_substr($this->getFirstName(), 0, 1) . '. ';
        if ($this->getMiddleName()) {
            $shortName .= mb_substr($this->getMiddleName(), 0, 1) . '.';
        }
        return $shortName;
    }
}
