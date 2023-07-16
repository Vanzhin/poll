<?php

namespace App\Repository\User\Filter;

class UserFilter implements \JsonSerializable
{
    const DEFAULT_PAGE = 1;
    const DEFAULT_LIMIT = 10;
    public static array $propertiesToSort = [
        "firstName" => "pr",
        "lastName" => "pr",
        "position" => "pr",
        "department" => "pr",
        "educationLevel" => "pr",
        "createdAt" => "u",
        "updatedAt" => "u"
    ];

    private ?string $generalSearch = null;
    private ?bool $isActive = null;

    private ?array $sort = null;

    public function __construct(private int $page, private int $limit)
    {
    }

    public static function createDefault(): self
    {
        $filter = new self(self::DEFAULT_PAGE, self::DEFAULT_LIMIT);
        $filter->addSort('lastName', 'ASC');
        return $filter;
    }

    /**
     * @return string|null
     */
    public function getGeneralSearch(): ?string
    {
        return $this->generalSearch;
    }

    /**
     * @param string|null $generalSearch
     */
    public function setGeneralSearch(?string $generalSearch): void
    {
        $this->generalSearch = $generalSearch;
    }

    /**
     * @return bool|null
     */
    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    /**
     * @param bool|null $isActive
     */
    public function setIsActive(?bool $isActive): void
    {
        $this->isActive = $isActive;
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