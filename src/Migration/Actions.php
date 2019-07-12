<?php declare(strict_types=1);

namespace PeeHaa\Migres\Migration;

use PeeHaa\Migres\Action\Action;
use PeeHaa\Migres\Action\CreateTable;
use PeeHaa\Migres\Action\RenameTable;

final class Actions implements \Iterator
{
    private string $tableName;

    /** @var array<Action> */
    private array $actions = [];

    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
    }

    public function prepend(Action $action): void
    {
        array_unshift($this->actions, $action);
    }

    public function add(Action $action): void
    {
        $this->actions[] = $action;
    }

    public function addAfterCreateAndRenameTable(Action $action): void
    {
        $position = 0;

        foreach ($this->actions as $index => $storedAction) {
            if ($storedAction instanceof CreateTable || $storedAction instanceof RenameTable) {
                continue;
            }

            $position = $index;

            break;
        }

        array_splice($this->actions, $position, 0, [$action]);
    }

    public function current()
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
