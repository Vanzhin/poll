<?php

namespace App\Entity;

use App\Repository\ResultRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ResultRepository::class)]
class Result
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['result', 'report'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['account', 'result'])]
    private ?int $score = null;

    #[ORM\OneToMany(mappedBy: 'result', targetEntity: Answer::class, orphanRemoval: true)]
    #[Groups(['result'])]
    private Collection $answers;

    #[ORM\ManyToOne(inversedBy: 'results')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['report'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'results')]
    #[Groups(['account', 'result'])]
    private ?Ticket $ticket = null;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['result'])]
    protected $updatedAt;

    #[ORM\ManyToOne(inversedBy: 'results')]
    #[Groups(['result'])]
    #[Assert\NotNull(message: 'result.test.not_null')]
    private ?Test $test = null;

    #[Groups(['result'])]
    private ?int $questionCount = null;

    #[Groups(['result'])]
    private ?int $correctQuestionCount = null;

    #[ORM\Column(length: 100, nullable: true)]
//    #[Assert\NotNull(message: 'result.mode.not_null')]
    #[Groups(['result'])]
    private ?string $mode = null;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

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
            $answer->setResult($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getResult() === $this) {
                $answer->setResult(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    public function setTicket(?Ticket $ticket): self
    {
        $this->ticket = $ticket;

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

    /**
     * @return int|null
     */
    public function getQuestionCount(): ?int
    {
        return $this->getAnswers()->count();
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(?string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCorrectQuestionCount(): ?int
    {
        return $this->getAnswers()->filter(function (Answer $answer) {
            return $answer->getCorrect();
        })->count();
    }
}
