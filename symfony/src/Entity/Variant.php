<?php

namespace App\Entity;

use App\Repository\VariantRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VariantRepository::class)]
#[UniqueEntity(
    fields: ['title', 'question'],
    message: 'Вариант с таким названием уже существует для этого вопроса',
)]
#[ORM\UniqueConstraint('variant_question_idx',['title', 'question_id'])]
// #[ORM\Table(name="ecommerce_products",uniqueConstraints={@UniqueConstraint(name="search_idx", columns={"name", "email"})})]

class Variant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['main'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['default' => 1])]
    private ?int $weight = null;

    #[ORM\ManyToOne(inversedBy: 'variant')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

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

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }
}
