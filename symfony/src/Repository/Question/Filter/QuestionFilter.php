<?php

namespace App\Repository\Question\Filter;

use App\Repository\Group\Filter\vo\DateTimeInterval;

class QuestionFilter implements \JsonSerializable
{
    const DEFAULT_PAGE = 1;
    const DEFAULT_LIMIT = 10;
    public static array $propertiesToSort = [
        "title",
        "created_at",
        "updated_at",
        "published_at",
        "section_id",
        "test_id",
        "author_id",
    ];

    private ?string $title = null;
    private ?string $section = null;
    private ?string $test = null;
    private ?string $author = null;
    private ?bool $is_published = null;
    private ?DateTimeInterval $dateInterval = null;
    private ?array $sort = null;

    public function __construct(private int $page, private int $limit)
    {
    }

    public static function createDefault(): self
    {
        $filter = new self(self::DEFAULT_PAGE, self::DEFAULT_LIMIT);
        $filter->addSort('title', 'ASC');
        return $filter;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getSection(): ?string
    {
        return $this->section;
    }

    /**
     * @param string|null $section
     */
    public function setSection(?string $section): void
    {
        $this->section = $section;
    }

    /**
     * @return string|null
     */
    public function getTest(): ?string
    {
        return $this->test;
    }

    /**
     * @param string|null $test
     */
    public function setTest(?string $test): void
    {
        $this->test = $test;
    }

    /**
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * @param string|null $author
     */
    public function setAuthor(?string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return DateTimeInterval|null
     */
    public function getDateInterval(): ?DateTimeInterval
    {
        return $this->dateInterval;
    }

    /**
     * @param DateTimeInterval|null $dateInterval
     */
    public function setDateInterval(?DateTimeInterval $dateInterval): void
    {
        $this->dateInterval = $dateInterval;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @return array|null
     */
    public function getSort(): ?array
    {
        return $this->sort;
    }

    /**
     * @param array|null $sort
     */
    public function setSort(?array $sort): void
    {
        $this->sort = $sort;
    }

    public function getOffset(): int
    {
        return ($this->getPage() - 1) * $this->getLimit();
    }

    public function addSort(string $field, string $direction): void
    {
        $this->sort[$field] = $direction;
    }

    /**
     * @return bool|null
     */
    public function getIsPublished(): ?bool
    {
        return $this->is_published;
    }

    /**
     * @param bool|null $is_published
     */
    public function setIsPublished(?bool $is_published): void
    {
        $this->is_published = $is_published;
    }

    public function hasDateIntervalFrom(): bool
    {
        return !is_null($this->dateInterval->getFrom());
    }

    public function hasDateIntervalTo(): bool
    {
        return !is_null($this->dateInterval->getTo());
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}