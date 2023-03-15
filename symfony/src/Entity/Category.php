<?php

namespace App\Entity;

use App\Interfaces\EntityWithImageInterface;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;


#[Gedmo\Tree(type: 'nested')]
#[ORM\Table(name: 'category')]
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[UniqueEntity(
    fields: ['title', 'parent'],
    message: 'category.title.unique',
    ignoreNull: false
)]
#[ORM\UniqueConstraint('title_lvl_idx', ['title', 'lvl'])]
class Category implements EntityWithImageInterface
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['main', 'admin', 'category'])]
    private ?int $id = null;

    #[ORM\Column(length: 500)]
    #[Groups(['main', 'admin', 'category'])]
    #[Assert\NotBlank(
        message: 'category.title.not_blank'
    )]
    #[Assert\Length(
        max: 500,
        maxMessage: 'category.title.not_blank'
    )]
    private ?string $title = null;

    #[Gedmo\TreeLeft]
    #[ORM\Column(name: 'lft', type: Types::INTEGER, nullable: true)]
    private ?int $lft;


    #[Gedmo\TreeLevel]
    #[ORM\Column(name: 'lvl', type: Types::INTEGER, nullable: true)]
    private ?int $level;


    #[Gedmo\TreeRight]
    #[ORM\Column(name: 'rgt', type: Types::INTEGER, nullable: true)]
    private ?int $rgt;


    #[Gedmo\TreeRoot]
    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(name: 'tree_root', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?self $root;


    #[Gedmo\TreeParent]
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?self $parent = null;


    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: Category::class)]
    #[ORM\OrderBy(['lft' => 'ASC'])]
    #[Groups(['admin', 'category'])]
    #[MaxDepth(1)]
    private Collection $children;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['main', 'admin', 'category'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['main', 'admin', 'category'])]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Test::class, cascade: ['persist', 'remove'])]
    #[Groups(['category'])]
    private Collection $test;

    public function __construct()
    {
        $this->test = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getRoot()
    {
        return $this->root;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function getLeft()
    {
        return $this->lft;
    }

    public function getRight()
    {
        return $this->rgt;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    /**
     * @return Collection<int, Test>
     */
    public function getTest(): Collection
    {
        return $this->test;
    }

    public function addTest(Test $test): self
    {
        if (!$this->test->contains($test)) {
            $this->test->add($test);
            $test->setCategory($this);
        }

        return $this;
    }

    public function removeTest(Test $test): self
    {
        if ($this->test->removeElement($test)) {
            // set the owning side to null (unless already changed)
            if ($test->getCategory() === $this) {
                $test->setCategory(null);
            }
        }

        return $this;
    }
}
