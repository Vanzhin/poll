<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[UniqueEntity(['title', 'test'], 'question.title.already_exist')]
class Question
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['main', 'admin', 'create', 'admin_section', 'admin_ticket', 'admin_question'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(
        message: 'question.title.not_blank'
    )]
    #[Groups(['main', 'admin', 'admin_section', 'admin_ticket', 'admin_question'])]
    private ?string $title = null;

    #[ORM\Column]
    #[Groups(['main', 'admin_question'])]
    private array $answer = [];

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[Assert\NotNull(
        message: 'question.type.invalid'
    )]
    #[Groups(['main', 'admin', 'admin_section', 'admin_ticket', 'admin_question'])]
    private ?Type $type = null;

    #[ORM\ManyToMany(targetEntity: Ticket::class, mappedBy: 'question')]
    #[Groups(['main', 'admin', 'admin_section', 'admin_question'])]
    private Collection $tickets;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Answer::class, orphanRemoval: true)]
    private Collection $answers;

    #[ORM\Column(nullable: true)]
    #[Groups(['main', 'admin', 'admin_question'])]
    private array $subTitle = [];

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['main', 'admin', 'admin_question'])]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[Groups(['admin', 'admin_ticket', 'admin_question'])]
    private ?Section $section = null;

    #[ORM\ManyToOne(inversedBy: 'question')]
    #[Assert\NotNull(
        message: 'question.test.not_blank'
    )]
    private ?Test $test = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    private ?User $author = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['admin_question'])]
    private ?\DateTimeInterface $publishedAt = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'subQuestions')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $subQuestions;

    #[ORM\ManyToMany(targetEntity: Variant::class, mappedBy: 'question')]
    private Collection $variants;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->answers = new ArrayCollection();
        $this->variants = new ArrayCollection();
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

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSubQuestions(): Collection
    {
        return $this->subQuestions;
    }

    public function addSubQuestion(self $subQuestions): self
    {
        if (!$this->subQuestions->contains($subQuestions)) {
            $this->subQuestions->add($subQuestions);
            $subQuestions->setParent($this);
        }

        return $this;
    }

    public function removeSubQuestion(self $subQuestions): self
    {
        if ($this->subQuestions->removeElement($subQuestions)) {
            // set the owning side to null (unless already changed)
            if ($subQuestions->getParent() === $this) {
                $subQuestions->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Variant>
     */
    public function getVariants(): Collection
    {
        return $this->variants;
    }

    public function addVariant(Variant $variant): self
    {
        if (!$this->variants->contains($variant)) {
            $this->variants->add($variant);
            $variant->addQuestion($this);
        }

        return $this;
    }

    public function removeVariant(Variant $variant): self
    {
        if ($this->variants->removeElement($variant)) {
            $variant->removeQuestion($this);
        }

        return $this;
    }
}
