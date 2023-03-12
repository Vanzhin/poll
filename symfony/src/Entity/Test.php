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
    #[Groups(['main', 'category', 'admin'])]
    private ?int $id = null;

    #[Assert\NotBlank(
        message: 'test.title.not_blank'
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'test.title.max_length',
    )]
    #[ORM\Column(length: 255)]
    #[Groups(['main', 'category', 'admin'])]
    private ?string $title = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['main', 'admin', 'category'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Gedmo\Slug(fields: ['title'])]
    #[Groups(['main', 'category', 'admin'])]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'test', targetEntity: Question::class, orphanRemoval: true)]
    private Collection $question;

    #[ORM\OneToMany(mappedBy: 'test', targetEntity: Section::class, orphanRemoval: true)]
    private Collection $section;

    #[ORM\OneToMany(mappedBy: 'test', targetEntity: Ticket::class, orphanRemoval: true)]
    #[Groups(['main', 'category', 'admin'])]
    private Collection $ticket;

    #[Assert\NotNull(
        message: 'test.category.exist'
    )]
    #[ORM\ManyToOne(inversedBy: 'test')]
    private ?Category $category = null;

    #[Groups(['admin'])]
    private ?int $questionCount = null;

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

    public function __construct()
    {
        $this->question = new ArrayCollection();
        $this->section = new ArrayCollection();
        $this->ticket = new ArrayCollection();
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
}
