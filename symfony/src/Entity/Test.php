<?php

namespace App\Entity;

use App\Repository\TestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: TestRepository::class)]
#[UniqueEntity(['title', 'category'], 'test.title.exist_in_category')]
class Test
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['main', 'category', 'admin', 'admin_test_general'])]
    private ?int $id = null;

    #[Assert\NotBlank(
        message: 'test.title.not_blank'
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'test.title.max_length',
    )]
    #[ORM\Column(length: 255)]
    #[Groups(['main', 'category', 'admin', 'admin_test_general', 'result'])]
    private ?string $title = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['main', 'admin', 'category', 'admin_test_general'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Gedmo\Slug(fields: ['title'])]
    #[Groups(['main', 'category', 'admin', 'admin_test_general'])]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'test', targetEntity: Question::class, orphanRemoval: true)]
    private Collection $question;

    #[ORM\OneToMany(mappedBy: 'test', targetEntity: Section::class, orphanRemoval: true)]
    private Collection $section;

    #[ORM\OneToMany(mappedBy: 'test', targetEntity: Ticket::class, orphanRemoval: true)]
    #[Groups(['main', 'admin'])]
    private Collection $ticket;

    #[Assert\NotNull(
        message: 'test.category.exist'
    )]
    #[ORM\ManyToOne(inversedBy: 'test')]
    #[Groups(['result'])]
    private ?Category $category = null;

    #[Groups(['admin_test_general', 'category'])]
    private ?int $questionCount = null;

    #[Groups(['admin_test_general', 'category'])]
    private ?int $sectionCount = null;

    #[Groups(['admin_test_general', 'category'])]
    private ?int $ticketCount = null;

    #[ORM\OneToMany(mappedBy: 'test', targetEntity: Result::class, orphanRemoval: true)]
    private Collection $results;

    /**
     * @return int|null
     */

    public function getQuestionCount(): ?int
    {
        return $this->questionCount;
    }

    /**
     * @param int|null $questionCount
     */
    public function setQuestionCount(?int $questionCount): void
    {
        $this->questionCount = $questionCount;
    }

    /**
     * @return int|null
     */
    public function getSectionCount(): ?int
    {
        return $this->sectionCount;
    }

    /**
     * @param int|null $sectionCount
     */
    public function setSectionCount(?int $sectionCount): void
    {
        $this->sectionCount = $sectionCount;
    }

    /**
     * @return int|null
     */
    public function getTicketCount(): ?int
    {
        return $this->ticketCount;
    }

    /**
     * @param int|null $ticketCount
     */
    public function setTicketCount(?int $ticketCount): void
    {
        $this->ticketCount = $ticketCount;
    }

    public function __construct()
    {
        $this->question = new ArrayCollection();
        $this->section = new ArrayCollection();
        $this->ticket = new ArrayCollection();
        $this->results = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestion(): Collection
    {
        return $this->question;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->question->contains($question)) {
            $this->question->add($question);
            $question->setTest($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->question->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getTest() === $this) {
                $question->setTest(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Section>
     */
    public function getSection(): Collection
    {
        return $this->section;
    }

    public function addSection(Section $section): self
    {
        if (!$this->section->contains($section)) {
            $this->section->add($section);
            $section->setTest($this);
        }

        return $this;
    }

    public function removeSection(Section $section): self
    {
        if ($this->section->removeElement($section)) {
            // set the owning side to null (unless already changed)
            if ($section->getTest() === $this) {
                $section->setTest(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTicket(): Collection
    {
        return $this->ticket;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->ticket->contains($ticket)) {
            $this->ticket->add($ticket);
            $ticket->setTest($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->ticket->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getTest() === $this) {
                $ticket->setTest(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
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
            $result->setTest($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->removeElement($result)) {
            // set the owning side to null (unless already changed)
            if ($result->getTest() === $this) {
                $result->setTest(null);
            }
        }

        return $this;
    }
}
