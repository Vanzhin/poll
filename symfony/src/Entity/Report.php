<?php

namespace App\Entity;

use App\Entity\User\User;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

class Report
{
    use TimestampableEntity;

//    #[Groups(['report'])]
    private ?int $id = null;

//    #[Groups(['report'])]
    private ?string $title = null;

//    #[Groups(['report'])]
    private ?Result $result = null;

//    #[Groups(['report'])]
    private ?Organization $organization = null;


    #[Groups(['report'])]
    private ?User $user = null;

    private array $report = [];

    /**
     * @return Organization|null
     */
    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    /**
     * @param Organization|null $organization
     */
    public function setOrganization(?Organization $organization): void
    {
        $this->organization = $organization;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
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

    public function getResult(): ?Result
    {
        return $this->result;
    }

    public function setResult(?Result $result): self
    {
        $this->result = $result;

        return $this;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function getReport(): array
    {
        return $this->report;
    }

    /**
     * @param array $report
     */
    public function setReport(array $report): void
    {
        $this->report = $report;
    }
}
