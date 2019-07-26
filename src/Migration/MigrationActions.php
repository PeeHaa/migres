<?php declare(strict_types=1);

namespace PeeHaa\Migres\Migration;

final class MigrationActions implements \Iterator
{
    /** @var array<TableActions> */
    private array $actions;

    public function __construct(TableActions ...$actions)
    {
        $this->actions = $actions;
    }

    public function reverse(): self
    {
        $tableActions = [];

        foreach (array_reverse($this->actions) as $tableAction) {
            $tableActions[] = $tableAction->reverse();
        }

        return new self(...$tableActions);
    }

    public function current(): TableActions
    {
        return current($this->actions);
    }

    public function next(): void
    {
        next($this->actions);
    }

    public function key(): ?int
    {
        return key($this->actions);
    }

    public function valid(): bool
    {
        return $this->key() !== null;
    }

    public function rewind(): void
    {
        reset($this->actions);
    }
}