<?php

namespace App\Entity;

use App\Repository\TestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;


#[ORM\Entity(repositoryClass: TestRepository::class)]
class Test
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['main', 'category'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['main', 'category'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['main'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Gedmo\Slug(fields: ['title'])]
    #[Groups(['main', 'category'])]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'test', targetEntity: Question::class)]
    private Collection $question;

    #[ORM\OneToMany(mappedBy: 'test', targetEntity: Section::class, orphanRemoval: true)]
    private Collection $section;

    #[ORM\OneToMany(mappedBy: 'test', targetEntity: Ticket::class)]
    #[Groups(['main', 'category'])]
    private Collection $ticket;

    #[ORM\ManyToOne(inversedBy: 'test')]
    private ?Category $category = null;

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
