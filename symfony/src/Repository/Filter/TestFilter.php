<?php

namespace App\Repository\Filter;

use App\Repository\Filter\vo\test\DateTime;
use Monolog\DateTimeImmutable;

class TestFilter
{
    const DEFAULT_PAGE = 1;
    const DEFAULT_LIMIT = 100;

    private ?string $title = null;
    private ?string $description = null;
    private ?array $dateInterval = null;
    private ?array $mintrudTests = null;
    private ?string $category = null;
    private ?array $sort = null;
    private int $page = self::DEFAULT_PAGE;
    private int $limit = self::DEFAULT_LIMIT;

    public function __construct()
    {

    }

    public static function createDefault(): self
    {
//        здесь будут дефолтные настройки
        return new self();
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDateInterval(?\DateTimeImmutable $from, ?\DateTimeImmutable $to): void
    {
        $this->dateInterval = ['from' => $from, 'to' => $to];
    }

    public function hasDateIntervalTo(): bool
    {
        return isset($this->dateInterval['to']);
    }

    public function hasDateIntervalFrom(): bool
    {
        return isset($this->dateInterval['from']);
    }


    /**
     * @return array|null
     */
    public function getDateInterval(): ?array
    {
        return $this->dateInterval;
    }

    /**
     * @return array|null
     */
    public function getMintrudTests(): ?array
    {
        return $this->mintrudTests;
    }

    /**
     * @return array|null
     */
    public function getSort(): ?array
    {
        return $this->sort;
    }


    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }


    public function setMintrudTests(string ...$mintrudTests): void
    {
        $this->mintrudTests = $mintrudTests;
    }

    /**
     * @param array|null $sort
     */
    public function setSort(?array $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string|null $category
     */
    public function setCategory(?string $category): void
    {
        $this->category = $category;
    }

    public function getOffset(): int
    {
        return ($this->getPage() - 1) * $this->getLimit();
    }

    /**
     * @param int|null $page
     */
    public function setPage(?int $page): void
    {
        $this->page = $page;
    }

    /**
     * @param int|null $limit
     */
    public function setLimit(?int $limit): void
    {
        $this->limit = $limit;
    }
}