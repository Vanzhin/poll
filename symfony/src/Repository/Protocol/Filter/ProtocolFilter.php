<?php

namespace App\Repository\Protocol\Filter;

use App\Repository\Shared\Filter\vo\DateTimeInterval;

class ProtocolFilter implements \JsonSerializable
{
    const DEFAULT_PAGE = 1;
    const DEFAULT_LIMIT = 10;
    public static array $propertiesToSort = [
        "test",
        "group",
        "started_at",
        "finished_at",
        "number",
        'file'
    ];

    private ?string $number = null;
    private ?string $group = null;
    private ?string $test = null;
    private ?bool $isFile = null;
    private ?DateTimeInterval $dateInterval = null;
    private ?array $sort = null;

    public function __construct(private int $page, private int $limit)
    {
    }

    public static function createDefault(): self
    {
        $filter = new self(self::DEFAULT_PAGE, self::DEFAULT_LIMIT);
        $filter->addSort('created_at', 'ASC');
        return $filter;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): void
    {
        $this->number = $number;
    }

    public function getGroup(): ?string
    {
        return $this->group;
    }

    public function setGroup(?string $group): void
    {
        $this->group = $group;
    }

    public function getTest(): ?string
    {
        return $this->test;
    }

    public function setTest(?string $test): void
    {
        $this->test = $test;
    }

    public function getIsFile(): ?bool
    {
        return $this->isFile;
    }

    public function setIsFile(?bool $isFile): void
    {
        $this->isFile = $isFile;
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