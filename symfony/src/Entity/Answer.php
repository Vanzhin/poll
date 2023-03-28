<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AnswerRepository::class)]
class Answer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['result'])]
    private ?int $id = null;

    #[ORM\Column]
//    #[Groups(['result'])]
    private array $content = [];

    #[ORM\ManyToOne(inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
//    #[Groups(['result'])]
    #[Groups(['result_answer'])]
    private ?Question $question = null;


    #[ORM\ManyToOne(inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Result $result = null;

//    #[Groups(['result'])]
    private ?bool $correct = null;

    /**
     * @return bool|null
     */
    public function getCorrect(): ?bool
    {
        $correct = false;
//        if(is_numeric($this->content[0])){
//            dd($this->question);
//        }

        if ($this->getQuestion()->getAnswer() === $this->content) {
            $correct = true;
        };

        return $correct;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): array
    {
        return $this->content;
    }

    public function setContent(array $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getResult(): ?Result
    {
        return $this->result;
    }

    public function setResult(?Result $result): self
    {
        $this->result = $result;

        return $this;
    }
}
