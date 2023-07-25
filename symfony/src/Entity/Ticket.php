<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: TicketRepository::class)]
#[UniqueEntity(['title', 'test'], 'ticket.title.already_exist')]
#[ORM\UniqueConstraint('title_test_idx', ['title', 'test_id'])]
class Ticket
{

    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['main', 'main_test', 'category', 'admin_section', 'admin_ticket', 'admin_question','test'])]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Question::class, inversedBy: 'tickets', orphanRemoval: true)]
    #[Groups(['main', 'admin_ticket'])]
    #[Assert\Count(
        min: 1,
        minMessage: 'ticket.question.not_null',
    )]
    private Collection $question;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: 'ticket.title.not_blank'
    )]
    #[Assert\Positive(
        message: 'ticket.title.not_int'
    )]
    #[Groups(['main', 'main_test', 'account', 'admin', 'category', 'admin_section', 'admin_ticket', 'admin_question', 'result', 'test'])]
    private ?int $title = null;

    #[ORM\OneToMany(mappedBy: 'ticket', targetEntity: Result::class, cascade: ['persist', 'remove'])]
    private Collection $results;

    #[ORM\ManyToOne(inversedBy: 'ticket')]
    #[Assert\NotNull(
        message: 'ticket.test.not_null'
    )]
    private ?Test $test = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $description = null;

    public function __construct()
    {
        $this->question = new ArrayCollection();
        $this->results = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        $this->question->removeElement($question);

        return $this;
    }

    public function getTitle(): ?int
    {
        return $this->title;
    }

    public function setTitle(?int $title): self
    {
        $this->title = $title;

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
            $result->setTicket($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->removeElement($result)) {
            // set the owning side to null (unless already changed)
            if ($result->getTicket() === $this) {
                $result->setTicket(null);
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
