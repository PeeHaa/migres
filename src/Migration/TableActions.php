<?php declare(strict_types=1);

namespace PeeHaa\Migres\Migration;

use PeeHaa\Migres\Action\Action;

final class TableActions implements \Iterator
{
    private string $name;

    /** @var array<Action> */
    private array $actions;

    public function __construct(string $name, Action ...$actions)
    {
        $this->name    = $name;
        $this->actions = $actions;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function current(): Action
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
