<?php

namespace App\Entity;

use App\Interfaces\EntityWithImageInterface;
use App\Repository\SubtitleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: SubtitleRepository::class)]
#[UniqueEntity(
    fields: ['title', 'question'],
    message: 'subtitle.title.unique',
)]
#[ORM\UniqueConstraint('subtitle_question_idx', ['title', 'question_id'])]
class Subtitle implements EntityWithImageInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['admin_question'])]
    private ?int $id = null;

    #[ORM\Column(length: 500)]
    #[Groups(['test', 'handle', 'admin_question'])]
    #[Assert\NotBlank(
        message: 'subtitle.title.not_blank'
    )]
    #[Assert\Length(
        max: 500,
        maxMessage: 'subtitle.title.max_length'
    )]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'subtitles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'subtitles')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['admin_question'])]
    private ?Variant $correct = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

}
