<?php

namespace App\Entity;

use App\Entity\Protocol\Protocol;
use App\Repository\Test\TestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: TestRepository::class)]
#[UniqueEntity(['title', 'category'], 'test.title.exist_in_category')]
class Test
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['main', 'main_test', 'category', 'admin', 'admin_test_general', 'search', 'admin_group', 'breadcrumbs', 'admin_protocol'])]
    private ?int $id = null;

    #[Assert\NotBlank(
        message: 'test.title.not_blank'
    )]
    #[Assert\Length(
        max: 750,
        maxMessage: 'test.title.max_length',
    )]
    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['main', 'main_test', 'category', 'admin', 'admin_test_general', 'result', 'search', 'test'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['main', 'main_test', 'admin', 'category', 'admin_test_general', 'test'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Gedmo\Slug(fields: ['title'])]
    #[Groups(['main', 'main_test', 'category', 'admin', 'admin_test_general', 'search'])]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'test', targetEntity: Question::class, orphanRemoval: true)]
    private Collection $question;

    #[ORM\OneToMany(mappedBy: 'test', targetEntity: Section::class, orphanRemoval: true)]
    #[Groups(['handle'])]
    private Collection $section;

    #[ORM\OneToMany(mappedBy: 'test', targetEntity: Ticket::class, orphanRemoval: true)]
    #[Groups(['main', 'main_test', 'admin'])]
    private Collection $ticket;

    #[Assert\NotNull(
        message: 'test.category.exist'
    )]
    #[ORM\ManyToOne(inversedBy: 'test')]
    #[Groups(['result', 'search', 'breadcrumbs'])]
    private ?Category $category = null;

    #[Groups(['admin_test_general', 'category', 'search'])]
    private ?int $questionCount = null;

    #[Groups(['admin_test_general', 'category', 'search'])]
    private ?int $questionUnPublishedCount = null;

    #[Groups(['admin_test_general', 'category', 'handle'])]
    private ?int $sectionCount = null;

    #[Groups(['admin_test_general', 'category'])]
    private ?int $ticketCount = null;

    #[ORM\OneToMany(mappedBy: 'test', targetEntity: Result::class, orphanRemoval: true)]
    private Collection $results;

    #[ORM\ManyToOne(inversedBy: 'tests')]
    #[Groups(['main', 'main_test', 'category', 'admin', 'admin_test_general', 'result', 'search', 'report'])]
    private ?MinTrudTest $minTrudTest = null;

    #[ORM\Column(options: ['default' => 6000])]
    #[Assert\GreaterThanOrEqual(
        value: 1200,
    )]
    #[Groups(['main', 'main_test', 'category', 'admin', 'admin_test_general', 'result', 'search'])]
    private ?int $time = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['default' => 1])]
    #[Groups(['handle'])]
    private int $sectionCountToPass;

    #[Groups(['handle'])]
    private int $sectionCountPassed = 0;

    #[Groups(['handle'])]
    private bool $pass = false;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(
        message: 'test.alias.not_blank'
    )]
    #[Assert\Length(
        max: 30,
        maxMessage: 'test.alias.max_length',
    )]
    #[Groups(['main', 'main_test', 'category', 'admin', 'admin_test_general', 'result', 'search', 'breadcrumbs'])]
    private ?string $alias = null;

    #[ORM\ManyToMany(targetEntity: Group::class, mappedBy: 'availableTests')]
    private Collection $groups;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['admin_test_general', 'main', 'test', 'main_test'])]
    #[Assert\Length(max: 100, maxMessage: 'test.robots.max_length')]
    private ?string $robots = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['admin_test_general', 'main', 'test', 'main_test'])]
    #[Assert\Length(max: 100, maxMessage: 'test.canonical.max_length')]
    private ?string $canonical = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['admin_test_general', 'main', 'test', 'main_test'])]
    private ?string $descriptionSeo = null;

    #[ORM\OneToMany(mappedBy: 'test', targetEntity: Protocol::class)]
    private Collection $protocols;

    /**
     * @return bool
     */
    public function isPass(): bool
    {
        return $this->sectionCountToPass <= $this->sectionCountPassed;
    }

    /**
     * @return int|null
     */
    public function getSectionCountPassed(): ?int
    {
        return $this->sectionCountPassed;
    }

    /**
     * @param int $sectionCountPassed
     */
    public function setSectionCountPassed(int $sectionCountPassed): void
    {
        $this->sectionCountPassed = $sectionCountPassed;
    }

    /**
     * @return int|null
     */

    public function getQuestionCount(): ?int
    {
        return $this->getQuestion()->count();
    }

    /**
     * @param int|null $questionCount
     */
    public function setQuestionCount(?int $questionCount): void
    {
        $this->questionCount = $questionCount;
    }

    /**
     * @return int|null
     */
    public function getSectionCount(): ?int
    {
        return $this->getSection()->count();
    }

    /**
     * @param int|null $sectionCount
     */
    public function setSectionCount(?int $sectionCount): void
    {
        $this->sectionCount = $sectionCount;
    }

    /**
     * @return int|null
     */
    public function getTicketCount(): ?int
    {
        return $this->getTicket()->count();
    }

    /**
     * @param int|null $ticketCount
     */
    public function setTicketCount(?int $ticketCount): void
    {
        $this->ticketCount = $ticketCount;
    }

    public function __construct()
    {
        $this->question = new ArrayCollection();
        $this->section = new ArrayCollection();
        $this->ticket = new ArrayCollection();
        $this->results = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->protocols = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
            $question->setTest($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->question->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getTest() === $this) {
                $question->setTest(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Section>
     */
    public function getSection(): Collection
    {
        return $this->section;
    }

    public function addSection(Section $section): self
    {
        if (!$this->section->contains($section)) {
            $this->section->add($section);
            $section->setTest($this);
        }

        return $this;
    }

    public function removeSection(Section $section): self
    {
        if ($this->section->removeElement($section)) {
            // set the owning side to null (unless already changed)
            if ($section->getTest() === $this) {
                $section->setTest(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTicket(): Collection
    {
        return $this->ticket;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->ticket->contains($ticket)) {
            $this->ticket->add($ticket);
            $ticket->setTest($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->ticket->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getTest() === $this) {
                $ticket->setTest(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

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
            $result->setTest($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->removeElement($result)) {
            // set the owning side to null (unless already changed)
            if ($result->getTest() === $this) {
                $result->setTest(null);
            }
        }

        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuestionUnPublishedCount(): ?int
    {
        return $this->getQuestion()->filter(function (Question $question) {
            return $question->getPublishedAt() === null;
        })->count();
    }

    /**
     * @param int|null $questionUnPublishedCount
     */
    public function setQuestionUnPublishedCount(?int $questionUnPublishedCount): void
    {
        $this->questionUnPublishedCount = $questionUnPublishedCount;
    }

    public function getMinTrudTest(): ?MinTrudTest
    {
        return $this->minTrudTest;
    }

    public function setMinTrudTest(?MinTrudTest $minTrudTest): self
    {
        $this->minTrudTest = $minTrudTest;

        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(int $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getSectionCountToPass(): ?int
    {
        return $this->sectionCountToPass;
    }

    public function setSectionCountToPass(?int $sectionCountToPass): self
    {
        $this->sectionCountToPass = $sectionCountToPass;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
            $group->addAvailableTest($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->removeElement($group)) {
            $group->removeAvailableTest($this);
        }

        return $this;
    }

    public function getRobots(): ?string
    {
        return $this->robots;
    }

    public function setRobots(?string $robots): self
    {
        $this->robots = $robots;

        return $this;
    }

    public function getCanonical(): ?string
    {
        return $this->canonical;
    }

    public function setCanonical(?string $canonical): self
    {
        $this->canonical = $canonical;

        return $this;
    }

    public function getDescriptionSeo(): ?string
    {
        return $this->descriptionSeo;
    }

    public function setDescriptionSeo(?string $descriptionSeo): self
    {
        $this->descriptionSeo = $descriptionSeo;

        return $this;
    }

    /**
     * @return Collection<int, Protocol>
     */
    public function getProtocols(): Collection
    {
        return $this->protocols;
    }

    public function addProtocol(Protocol $protocol): self
    {
        if (!$this->protocols->contains($protocol)) {
            $this->protocols->add($protocol);
            $protocol->setTest($this);
        }

        return $this;
    }

    public function removeProtocol(Protocol $protocol): self
    {
        if ($this->protocols->removeElement($protocol)) {
            // set the owning side to null (unless already changed)
            if ($protocol->getTest() === $this) {
                $protocol->setTest(null);
            }
        }

        return $this;
    }
}
