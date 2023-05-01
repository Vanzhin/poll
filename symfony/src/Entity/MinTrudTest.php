<?php

namespace App\Entity;

use App\Repository\MinTrudTestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: MinTrudTestRepository::class)]
#[UniqueEntity(
    fields: ['originalId'],
    message: 'min_trud_test.original_id.unique',
)]
class MinTrudTest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['main', 'main_test', 'category', 'admin', 'admin_test_general', 'result'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(
        message: 'min_trud_test.title.not_blank'
    )]
    #[Groups(['main', 'main_test', 'category', 'admin', 'admin_test_general', 'result'])]
    private ?string $title = null;

    #[ORM\Column]
    #[Groups(['admin'])]

    private ?int $originalId = null;

    #[ORM\OneToMany(mappedBy: 'minTrudTest', targetEntity: Test::class)]
    private Collection $tests;

    public function __construct()
    {
        $this->tests = new ArrayCollection();
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

    public function getOriginalId(): ?int
    {
        return $this->originalId;
    }

    public function setOriginalId(int $originalId): self
    {
        $this->originalId = $originalId;

        return $this;
    }

    /**
     * @return Collection<int, Test>
     */
    public function getTests(): Collection
    {
        return $this->tests;
    }

    public function addTest(Test $test): self
    {
        if (!$this->tests->contains($test)) {
            $this->tests->add($test);
            $test->setMinTrudTest($this);
        }

        return $this;
    }

    public function removeTest(Test $test): self
    {
        if ($this->tests->removeElement($test)) {
            // set the owning side to null (unless already changed)
            if ($test->getMinTrudTest() === $this) {
                $test->setMinTrudTest(null);
            }
        }

        return $this;
    }
}
