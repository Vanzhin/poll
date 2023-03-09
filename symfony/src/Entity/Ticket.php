<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{

    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['main', 'category','admin_section', 'admin_ticket', 'admin_question'])]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Question::class, inversedBy: 'tickets')]
    #[Groups(['main', 'admin_ticket'])]
    private Collection $question;

    #[ORM\Column(length: 255)]
    #[Groups(['main', 'account', 'admin', 'category', 'admin_section', 'admin_ticket', 'admin_question'])]
    private ?string $title = null;

    #[ORM\OneToMany(mappedBy: 'ticket', targetEntity: Result::class, cascade: ['persist', 'remove'])]
    private Collection $results;

    #[ORM\ManyToOne(inversedBy: 'ticket')]
    private ?Test $test = null;

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
}
