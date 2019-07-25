<?php declare(strict_types=1);

namespace PeeHaa\Migres\Migration;

use PeeHaa\Migres\Action\AddColumn;
use PeeHaa\Migres\Action\ChangeColumn;
use PeeHaa\Migres\Action\RemoveColumn;
use PeeHaa\Migres\Action\RenameColumn;
use PeeHaa\Migres\Column;
use PeeHaa\Migres\Migration;

final class Migrations implements \Iterator
{
    /** @var array<int,Migration> */
    private array $migrations = [];

    public function __construct(Migration ...$migrations)
    {
        $this->migrations = $migrations;
    }

    public function findColumnStateBeforeAction(RemoveColumn $action, $tableName): ?Column
    {
        $column = null;

        // @todo: handle TableRenameActions
        foreach ($this->migrations as $migration) {
            foreach ($migration->getActions() as $migrationAction) {
                if ($migrationAction->getTableName() !== $tableName) {
                    continue;
                }

                foreach ($migrationAction->getActions() as $previousAction) {
                    if (!$previousAction instanceof AddColumn && !$previousAction instanceof ChangeColumn && !$previousAction instanceof RenameColumn) {
                        continue;
                    }

                    if ($previousAction instanceof AddColumn || $previousAction instanceof ChangeColumn) {
                        if ($previousAction->getColumn()->getName() !== $action->getName()) {
                            continue;
                        }

                        $column = $previousAction->getColumn();
                    }

                    if ($previousAction instanceof RenameColumn) {
                        if ($previousAction->getColumn()->getName() !== $action->getName()) {
                            continue;
                        }

                        $column = new Column(
                            $previousAction->getColumn()->getName(),
                            $previousAction->getColumn()->getType(),
                            $previousAction->getColumn()->getOptions()
                        );
                    }
                }
            }
        }

        return $column;
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
