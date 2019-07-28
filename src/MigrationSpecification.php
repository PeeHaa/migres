<?php declare(strict_types=1);

namespace PeeHaa\Migres;

use PeeHaa\Migres\Migration\TableActions;
use PeeHaa\Migres\Specification\Table;

abstract class MigrationSpecification
{
    /** @var array<Table> */
    private array $migrationSteps = [];

    abstract public function change(): void;

    public function createTable(string $name, callable $callback): void
    {
        $table = Table::fromCreateTable($name);

        $callback($table);

        $this->migrationSteps[] = $table;
    }

    public function changeTable(string $name, callable $callback): void
    {
        $table = Table::fromChangeTable($name);

        $callback($table);

        $this->migrationSteps[] = $table;
    }

    public function renameTable(string $oldName, string $newName): void
    {
        $this->migrationSteps[] = Table::fromRenameTable($oldName, $newName);
    }

    public function dropTable(string $name): void
    {
        $this->migrationSteps[] = Table::fromDropTable($name);
    }

    /**
     * @internal
     * @return array<TableActions>
     */
    public function getMigrationSteps(): array
    {
        $actions = [];

        /** @var Table $table */
        foreach ($this->migrationSteps as $migrationStep) {
            $actions[] = $migrationStep->getActions();
        }

        return $actions;
    }
}
