<?php

namespace App\Entity;

use App\Repository\SubtitleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SubtitleRepository::class)]
class Subtitle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 500)]
    #[Groups(['test', 'handle'])]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'subtitles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    #[ORM\ManyToOne(inversedBy: 'subtitles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Variant $correct = null;

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

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getCorrect(): ?Variant
    {
        return $this->correct;
    }

    public function setCorrect(?Variant $correct): self
    {
        $this->correct = $correct;

        return $this;
    }
}
