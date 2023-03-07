<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['main', 'admin', 'create', 'admin_section', 'admin_ticket'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['main', 'admin', 'admin_section', 'admin_ticket'])]
    private ?string $title = null;

    #[ORM\Column]
    #[Groups(['main'])]
    private array $answer = [];

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[Groups(['main', 'admin', 'admin_section', 'admin_ticket'])]
    private ?Type $type = null;

    #[ORM\ManyToMany(targetEntity: Ticket::class, mappedBy: 'question')]
    #[Groups(['main', 'admin', 'admin_section'])]
    private Collection $tickets;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Answer::class, orphanRemoval: true)]
    private Collection $answers;

    #[ORM\Column(nullable: true)]
    #[Groups(['main', 'admin'])]
    private array $subTitle = [];

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Variant::class, cascade: ["persist", "remove"])]
    #[Groups(['main'])]
    private Collection $variant;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['main', 'admin'])]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[Groups(['admin', 'admin_ticket'])]
    private ?Section $section = null;

    #[ORM\ManyToOne(inversedBy: 'question')]
    private ?Test $test = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    private ?User $author = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $publishedAt = null;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->answers = new ArrayCollection();
        $this->variant = new ArrayCollection();
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

    public function getAnswer(): array
    {
        return $this->answer;
    }

    public function setAnswer(array $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->addQuestion($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            $ticket->removeQuestion($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
    }

    public function getSubTitle(): array
    {
        return $this->subTitle;
    }

    public function setSubTitle(?array $subTitle): self
    {
        $this->subTitle = $subTitle;

        return $this;
    }

    /**
     * @return Collection<int, Variant>
     */
    public function getVariant(): Collection
    {
        return $this->variant;
    }

    public function addVariant(Variant $variant): self
    {
        if (!$this->variant->contains($variant)) {
            $this->variant->add($variant);
            $variant->setQuestion($this);
        }

        return $this;
    }

    public function removeVariant(Variant $variant): self
    {
        if ($this->variant->removeElement($variant)) {
            // set the owning side to null (unless already changed)
            if ($variant->getQuestion() === $this) {
                $variant->setQuestion(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): self
    {
        $this->section = $section;

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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }
}
