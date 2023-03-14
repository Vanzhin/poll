<?php

namespace App\Entity;

use App\Repository\VariantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: VariantRepository::class)]
#[UniqueEntity(
    fields: ['title', 'question'],
    message: 'variant.title.unique',
)]
//#[ORM\UniqueConstraint('variant_question_idx', ['title', 'question_id'])]
class Variant
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['main', 'admin', 'admin_question'])]
    private ?int $id = null;

    #[ORM\Column(length: 700)]
    #[Assert\NotBlank(
        message: 'variant.title.not_blank'
    )]
    #[Assert\Length(
        max: 700,
        maxMessage: 'variant.title.max_length'
    )]
    #[Groups(['main', 'admin', 'admin_question'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['default' => 1])]
    #[Assert\LessThanOrEqual(
        value: 100,
        message: 'variant.weight.greater_than'
    )]
    private ?int $weight = null;

    #[ORM\ManyToOne(inversedBy: 'variant')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(
        message: 'variant.test.not_null'
    )]

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['main', 'admin', 'admin_question'])]
    private ?string $image = null;

    private bool|null $isCorrect = null;

    #[ORM\Column(nullable: true)]
    private ?bool $correct = null;

    #[ORM\ManyToMany(targetEntity: Question::class, inversedBy: 'variants')]
    private Collection $question;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $sequence = null;

    public function __construct()
    {
        $this->question = new ArrayCollection();
    }

    /**
     * @return bool|null
     */
    public function getIsCorrect(): ?bool
    {
        return $this->isCorrect;
    }

    /**
     * @param bool|null $isCorrect
     */
    public function setIsCorrect(?bool $isCorrect): void
    {
        $this->isCorrect = $isCorrect;
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

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

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

    public function isCorrect(): ?bool
    {
        return $this->correct;
    }

    public function setCorrect(?bool $correct): self
    {
        $this->correct = $correct;

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
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        $this->question->removeElement($question);

        return $this;
    }

    public function getSequence(): ?int
    {
        return $this->sequence;
    }

    public function setSequence(?int $sequence): self
    {
        $this->sequence = $sequence;

        return $this;
    }
}
