<?php declare(strict_types=1);

namespace PeeHaa\Migres\Migration;

use PeeHaa\Migres\Action\Action;

final class TableActions implements \Iterator
{
    private string $originalTableName;

    private string $tableName;

    private Actions $actions;

    public function __construct(string $originalTableName, string $tableName, Actions $actions)
    {
        //var_dump($tableName . '!!!!!');
        $this->originalTableName = $originalTableName;
        $this->tableName         = $tableName;
        $this->actions           = $actions;
    }

    public function getOriginalTableName(): string
    {
        return $this->originalTableName;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function current(): Action
    {
        return $this->actions->current();
    }

    public function next(): void
    {
        $this->actions->next();
    }

    public function key(): ?int
    {
        return $this->actions->key();
    }

    public function valid(): bool
    {
        return $this->actions->valid();
    }

    public function rewind(): void
    {
        $this->actions->rewind();
    }
}
