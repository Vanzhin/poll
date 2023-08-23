<?php

namespace App\Entity\User\vo;

use App\Entity\Organization;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


class WorkerCard
{
    #[ORM\Column(nullable: true)]
    #[Groups(['account', 'user', 'admin_user', 'report'])]
    #[Assert\NotBlank(message: 'user.first_name.not_blank')]
    private string|null $firstName = null;

    #[Assert\NotBlank(message: 'user.first_name.not_blank')]
    #[Groups(['report'])]
    private string|null $lastName = null;

    #[Assert\NotBlank(message: 'worker_card.middle_name.not_blank')]
    #[Groups(['report'])]
    private string|null $middleName = null;

    #[Assert\Regex(pattern: '/^\d{3}-\d{3}-\d{3} \d{2}$/', message: 'worker_card.snils.format')]
    #[Assert\NotBlank(message: 'worker_card.snils.not_blank')]
    #[Groups(['report'])]
    private string|null $snils = null;

    #[Assert\NotBlank(message: 'worker_card.position.not_blank')]
    #[Groups(['report'])]
    private string|null $position = null;

    #[Assert\NotNull(message: 'worker_card.position.not_blank')]
    private ?Organization $organization = null;


    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     * @return WorkerCard
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string|null
     */
    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    /**
     * @param string|null $middleName
     */
    public function setMiddleName(?string $middleName): void
    {
        $this->middleName = $middleName;
    }

    /**
     * @return string|null
     */
    public function getSnils(): ?string
    {
        return $this->snils;
    }

    /**
     * @param string|null $snils
     */
    public function setSnils(?string $snils): void
    {
        $this->snils = $snils;
    }

    /**
     * @return string|null
     */
    public function getPosition(): ?string
    {
        return $this->position;
    }

    /**
     * @param string|null $position
     */
    public function setPosition(?string $position): void
    {
        $this->position = $position;
    }

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(?Organization $organization): self
    {
        $this->organization = $organization;

        return $this;
    }



}