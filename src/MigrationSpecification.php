<?php declare(strict_types=1);

namespace PeeHaa\Migres;

use PeeHaa\Migres\Migration\MigrationActions;
use PeeHaa\Migres\Migration\Migrations;
use PeeHaa\Migres\Migration\TableActions;

abstract class MigrationSpecification
{
    /** @var array<Table> */
    private array $tables = [];

    abstract public function change(): void;

    public function table(string $name): Table
    {
        $table = new Table($name);

        $this->tables[] = $table;

        return $table;
    }

    /**
     * @internal
     */
    public function up(): MigrationActions
    {
        $actionsToRun = [];

        /** @var Table $table */
        foreach ($this->tables as $table) {
            $actionsToRun[] = new TableActions($table->getName(), $table->up());
        }

        return new MigrationActions(...$actionsToRun);
    }

    /**
     * @internal
     */
    public function down(Migrations $migrations): MigrationActions
    {
        $actionsToRun = [];

        /** @var Table $table */
        foreach (array_reverse($this->tables) as $table) {
            $actionsToRun[] = new TableActions($table->getName(), $table->down($migrations));
        }

        return new MigrationActions(...$actionsToRun);
    }
}
