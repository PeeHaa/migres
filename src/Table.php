<?php declare(strict_types=1);

namespace PeeHaa\Migres;

use PeeHaa\Migres\Action\AddColumn;
use PeeHaa\Migres\Action\AddConstraint;
use PeeHaa\Migres\Action\AddIndex;
use PeeHaa\Migres\Action\AddPrimaryKey;
use PeeHaa\Migres\Action\ChangeColumn;
use PeeHaa\Migres\Action\CreateTable;
use PeeHaa\Migres\Action\DropTable;
use PeeHaa\Migres\Action\RemoveColumn;
use PeeHaa\Migres\Action\RemoveConstraint;
use PeeHaa\Migres\Action\RemoveIndex;
use PeeHaa\Migres\Action\RenameColumn;
use PeeHaa\Migres\Action\RenameTable;
use PeeHaa\Migres\Constraint\Check;
use PeeHaa\Migres\Constraint\CombinedPrimaryKey;
use PeeHaa\Migres\Constraint\CombinedUnique;
use PeeHaa\Migres\Constraint\Index;
use PeeHaa\Migres\Constraint\NotNull;
use PeeHaa\Migres\DataType\Factory;
use PeeHaa\Migres\Migration\Actions;
use PeeHaa\Migres\Migration\Table\Change;
use PeeHaa\Migres\Migration\Table\Create;
use PeeHaa\Migres\Migration\Table\Migration;

final class Table
{
    private Factory $dataTypeFactory;

    private string $name;

    private Actions $actions;

    private ?Migration $migration = null;

    public function __construct(string $name)
    {
        $this->dataTypeFactory = new Factory();

        $this->name    = $name;
        $this->actions = new Actions($name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function drop(): self
    {
        $this->actions->add(new DropTable());

        return $this;
    }

    public function rename(string $newName): self
    {
        $this->actions->addAfterCreateAndRenameTable(new RenameTable($this->name, $newName));

        $this->name = $newName;

        return $this;
    }

    /**
     * @param array<string,mixed> $columnOptions
     * @throws \Exception
     */
    public function addColumn(string $name, string $type, array $columnOptions = []): self
    {
        $type = $this->dataTypeFactory->buildFromSpecificationString($type);

        $columnOptions = $this->getColumnOptionsFromArray($columnOptions);

        $this->actions->add(new AddColumn(new Column($name, $type, $columnOptions)));

        return $this;
    }

    public function renameColumn(string $oldName, string $newName): self
    {
        $this->actions->add(new RenameColumn($oldName, $newName));

        return $this;
    }

    public function removeColumn(string $name): self
    {
        $this->actions->add(new RemoveColumn($name));

        return $this;
    }

    /**
     * @param array<string,mixed> $columnOptions
     * @throws \Exception
     */
    public function changeColumn(string $name, string $type, array $columnOptions = []): self
    {
        $type = $this->dataTypeFactory->buildFromSpecificationString($type);

        $columnOptions = $this->getColumnOptionsFromArray($columnOptions);

        $this->actions->add(new ChangeColumn(new Column($name, $type, $columnOptions)));

        return $this;
    }

    /**
     * @param array<string> $columns
     * @param array<string,string> $options
     */
    public function addIndex(string $name, array $columns, array $options = []): self
    {
        if (isset($options['unique']) && $options['unique'] === true) {
            return $this->addUniqueConstraint($name, ...$columns);
        }

        $this->actions->add(new AddIndex(new Index($name, $this->name, $columns, $options['method'] ?? null)));

        return $this;
    }

    private function addUniqueConstraint(string $name, string ...$columns): self
    {
        $this->actions->add(new AddConstraint(new CombinedUnique($name, ...$columns)));

        return $this;
    }

    public function addCheck(string $name, string $expression): self
    {
        $this->actions->add(new AddConstraint(new Check($name, $expression)));

        return $this;
    }

    public function addPrimaryKey(string $name, string ...$columns): self
    {
        $this->actions->add(new AddPrimaryKey(new CombinedPrimaryKey($name, ...$columns)));

        return $this;
    }

    public function removeIndex(string $name): self
    {
        $this->actions->add(new RemoveIndex($name));

        return $this;
    }

    public function removeCheck(string $name): self
    {
        $this->actions->add(new RemoveConstraint($name));

        return $this;
    }

    public function removePrimaryKey(string $name): self
    {
        $this->actions->add(new RemoveConstraint($name));

        return $this;
    }

    public function create(): void
    {
        $this->actions->prepend(new CreateTable());

        $this->migration = new Create($this->name, $this->actions);
    }

    public function change(): void
    {
        $this->migration = new Change($this->name, $this->actions);
    }

    /**
     * @internal
     */
    public function getActions(): Actions
    {
        if ($this->migration === null) {
            // @todo: log warning

            return new Actions($this->name);
        }

        return $this->migration->getActions();
    }

    /**
     * @param array<string,mixed> $options
     */
    private function getColumnOptionsFromArray(array $options): ColumnOptions
    {
        $columnOptions = new ColumnOptions();

        if (isset($options['default'])) {
            $columnOptions->setDefault($options['default']);
        }

        if (isset($options['null']) && $options['null'] === false) {
            $columnOptions->addConstraint(new NotNull());
        }

        return $columnOptions;
    }
}
