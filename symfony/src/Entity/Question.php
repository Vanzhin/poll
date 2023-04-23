<?php

namespace App\Entity;

use App\Interfaces\EntityWithImageInterface;
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
class Question implements EntityWithImageInterface
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        'main',
        'admin',
        'create',
        'admin_section',
        'admin_ticket',
        'admin_question',
        'test',
        'handle',
        'result',
        'admin_section',
        'result_answer'
    ])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(
        max: 2500,
        maxMessage: 'question.title.max_length'
    )]
    #[Assert\NotBlank(
        message: 'question.title.not_blank'
    )]
    #[Groups(['main', 'admin', 'admin_section', 'admin_ticket', 'admin_question', 'test', 'handle', 'result', 'result_answer'])]
    private ?string $title = null;

//    #[ORM\Column]
    #[Groups(['admin_question'])]
    private array $answer = [];

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[Assert\NotNull(
        message: 'question.type.invalid'
    )]
    #[Groups(['main', 'admin', 'admin_section', 'admin_ticket', 'admin_question', 'test', 'handle', 'result', 'result_answer'])]
    private ?Type $type = null;

    #[ORM\ManyToMany(targetEntity: Ticket::class, mappedBy: 'question')]
    #[Groups(['main', 'admin', 'admin_section', 'admin_question'])]
    private Collection $tickets;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Answer::class, orphanRemoval: true)]
    private Collection $answers;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Variant::class, cascade: ["persist", "remove"])]
    #[Groups(['main', 'admin_question', 'test', 'handle', 'result', 'result_answer'])]
    #[ORM\OrderBy(["correct" => "ASC"])]
    private Collection $variant;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['main', 'admin', 'admin_question', 'test', 'handle', 'result', 'result_answer'])]
    private ?string $image = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'questions')]
    #[ORM\JoinColumn(name: "section_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
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

    #[Groups(['handle', 'result', 'result_answer'])]
    private ?array $result = null;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Subtitle::class, cascade: ['persist'], orphanRemoval: true)]
    #[Groups(['main', 'admin', 'admin_question', 'test', 'handle', 'result_answer'])]
    private Collection $subtitles;


    private array $subtitleIds;

    /**
     * @return array|null
     */
    public function getResult(): ?array
    {
        return $this->result;
    }

    /**
     * @param array|null $result
     */
    public function setResult(?array $result): void
    {
        $this->result = $result;
    }

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->answers = new ArrayCollection();
        $this->variant = new ArrayCollection();
        $this->subtitles = new ArrayCollection();
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
        $answer = [];

        switch ($this->getType()->getTitle()) {
            case 'input_one':
                $answer[] = $this->getVariant()->first()->getId();
                break;

            case 'radio':
            case 'checkbox':
                foreach ($this->getVariant() as $variant) {
                    if (!is_null($variant->getCorrect())) {
                        $answer[] = $variant->getId();
                    }
                }
                break;
            case 'conformity':
                foreach ($this->getSubtitles() as $subtitle) {
                    $answer[] = (int)$subtitle->getCorrect()->getId();
                }

                break;
            case 'order':
                foreach ($this->getVariant() as $variant) {

                    if (!is_null($variant->getCorrect())) {
                        $answer[$variant->getCorrect()] = $variant->getId();
                    }
                }
                ksort($answer);
                break;
//            case 'input_many':
//            case 'blank':

        }
        return $answer;
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

    /**
     * @return Collection<int, Subtitle>
     */
    public function getSubtitles(): Collection
    {
        return $this->subtitles;
    }

    public function addSubtitle(Subtitle $subtitle): self
    {
        if (!$this->subtitles->contains($subtitle)) {
            $this->subtitles->add($subtitle);
            $subtitle->setQuestion($this);
        }

        return $this;
    }

    public function removeSubtitle(Subtitle $subtitle): self
    {
        if ($this->subtitles->removeElement($subtitle)) {
            // set the owning side to null (unless already changed)
            if ($subtitle->getQuestion() === $this) {
                $subtitle->setQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getSubtitleIds(): array
    {
        foreach ($this->getSubtitles() as $subtitle) {
            $subtitleIds[] = (int)$subtitle->getId();
        }
        return $subtitleIds;
    }

}
