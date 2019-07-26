<?php declare(strict_types=1);

namespace PeeHaa\Migres\Migration;

use PeeHaa\Migres\Migration;

final class Migrations implements \Iterator
{
    /** @var array<int,Migration> */
    private array $migrations = [];

    public function __construct(Migration ...$migrations)
    {
        $this->migrations = $migrations;
    }

    public function current(): Migration
    {
        return current($this->migrations);
    }

    public function next(): void
    {
        next($this->migrations);
    }

    public function key(): ?int
    {
        return key($this->migrations);
    }

    public function valid(): bool
    {
        return $this->key() !== null;
    }

    public function rewind(): void
    {
        reset($this->migrations);
    }
}
