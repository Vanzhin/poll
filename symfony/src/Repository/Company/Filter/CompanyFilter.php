<?php

namespace App\Repository\Company\Filter;

class CompanyFilter implements \JsonSerializable
{
    const DEFAULT_PAGE = 1;
    const DEFAULT_LIMIT = 10;

    private ?string $title = null;
    private ?array $dateInterval = null;
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

    public function addSort(string $field, string $direction): void
    {
        $this->sort[$field] = $direction;
    }

    /**
     * @return array|null
     */
    public function getSort(): ?array
    {
        return $this->sort;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

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

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return array|null
     */
    public function getDateInterval(): ?array
    {
        return $this->dateInterval;
    }

    /**
     * @param \DateTimeImmutable|null $from
     * @param \DateTimeImmutable|null $to
     */
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