<?php

namespace App\Entity;

use App\Repository\SectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SectionRepository::class)]
#[UniqueEntity(['title', 'test'], 'section.title.already_exist')]
#[ORM\UniqueConstraint('title_test_idx', ['title', 'test_id'])]
class Section
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['admin', 'admin_section', 'admin_ticket', 'admin_question', 'admin_test_section'])]
    private ?int $id = null;

    #[Groups(['admin', 'admin_section', 'admin_ticket', 'admin_question', 'admin_test_section', 'handle'])]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: 'section.title.not_blank'
    )]
    private ?string $title = null;

    #[ORM\OneToMany(mappedBy: 'section', targetEntity: Question::class)]
    #[Groups(['admin', 'admin_section'])]
    private Collection $questions;

    #[ORM\ManyToOne(inversedBy: 'section')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(
        message: 'section.test.not_null'
    )]
    private ?Test $test = null;

    #[Groups(['admin_test_section', 'handle'])]
    private ?int $questionCount = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['default' => 1])]
    #[Groups(['handle', 'admin_test_section'])]
    private int $questionCountToPass;

    #[Groups(['handle'])]
    private int $questionCountPassed = 0;

    #[Groups(['handle'])]
    private ?bool $pass = null;

    /**
     * @return int|null
     */
    #[Groups(['admin_test_section'])]
    public function getQuestionCount(): ?int
    {
        return $this->getQuestions()->count();
    }

    public function __construct()
    {
        $this->questions = new ArrayCollection();
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
            $question->setSection($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getSection() === $this) {
                $question->setSection(null);
            }
        }

        return $this;
    }

    public function getTest(): ?Test
    {
        return $this->test;
    }

    public function setTest(?Test $test): self
    {
        $this->test = $test;

        return $this;
    }

    public function getQuestionCountToPass(): int
    {
        return $this->questionCountToPass;
    }

    public function setQuestionCountToPass(int $questionCountToPass): self
    {
        $this->questionCountToPass = $questionCountToPass;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuestionCountPassed(): int
    {
        return $this->questionCountPassed;
    }

    /**
     * @param int|null $questionCountPassed
     */
    public function setQuestionCountPassed(?int $questionCountPassed): void
    {
        $this->questionCountPassed = $questionCountPassed;
    }

    /**
     * @return bool|null
     */
    public function getPass(): ?bool
    {
        return $this->questionCountToPass <= $this->questionCountPassed;
    }
}
