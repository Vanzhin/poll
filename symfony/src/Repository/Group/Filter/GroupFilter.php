<?php

namespace App\Repository\Group\Filter;

use App\Repository\Group\Filter\vo\DateTimeInterval;

class GroupFilter implements \JsonSerializable
{
    const DEFAULT_PAGE = 1;
    const DEFAULT_LIMIT = 10;
    public static array $propertiesToSort = [
        "title",
        "started_at",
        "finished_at",
        "owner"
    ];

    private ?string $title = null;
    private ?string $owner = null;
    private ?DateTimeInterval $dateInterval = null;
    private ?array $sort = null;

    public function __construct(private int $page, private int $limit)
    {
    }

    public static function createDefault(): self
    {
        $filter = new self(self::DEFAULT_PAGE, self::DEFAULT_LIMIT);
        $filter->addSort('started_at', 'ASC');
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
    public function getOwner(): ?string
    {
        return $this->owner;
    }

    /**
     * @param string|null $owner
     */
    public function setOwner(?string $owner): void
    {
        $this->owner = $owner;
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


    public function hasDateIntervalStarted(): bool
    {
        return !is_null($this->dateInterval->getFrom());
    }

    public function hasDateIntervalFinished(): bool
    {
        return !is_null($this->dateInterval->getTo());
    }


    /**
     * @return array|null
     */
    public function getSort(): ?array
    {
        return $this->sort;
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

    public function getOffset(): int
    {
        return ($this->getPage() - 1) * $this->getLimit();
    }

    public function addSort(string $field, string $direction): void
    {
        $this->sort[$field] = $direction;
    }

    /**
     * @param array|null $sort
     */
    public function setSort(?array $sort): void
    {
        $this->sort = $sort;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}