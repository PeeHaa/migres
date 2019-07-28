<?php declare(strict_types=1);

namespace PeeHaa\Migres\Specification;

use PeeHaa\Migres\Action\Action;
use PeeHaa\Migres\Action\AddCheck;
use PeeHaa\Migres\Action\AddColumn;
use PeeHaa\Migres\Action\AddIndex;
use PeeHaa\Migres\Action\AddPrimaryKey;
use PeeHaa\Migres\Action\AddUniqueConstraint;
use PeeHaa\Migres\Action\ChangeColumn;
use PeeHaa\Migres\Action\CreateTable;
use PeeHaa\Migres\Action\DropCheck;
use PeeHaa\Migres\Action\DropColumn;
use PeeHaa\Migres\Action\DropIndex;
use PeeHaa\Migres\Action\DropPrimaryKey;
use PeeHaa\Migres\Action\DropTable;
use PeeHaa\Migres\Action\DropUniqueConstraint;
use PeeHaa\Migres\Action\RenameColumn;
use PeeHaa\Migres\Action\RenamePrimaryKey;
use PeeHaa\Migres\Action\RenameTable;
use PeeHaa\Migres\Constraint\Check;
use PeeHaa\Migres\Constraint\Index;
use PeeHaa\Migres\Constraint\PrimaryKey;
use PeeHaa\Migres\Constraint\Unique;
use PeeHaa\Migres\DataType\Type;
use PeeHaa\Migres\Migration\TableActions;

final class Table
{
    private string $name;

    /** @var array<Action> */
    private array $actions = [];

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function fromCreateTable(string $name): self
    {
        $table = new self($name);

        $table->actions[] = new CreateTable($name);

        return $table;
    }

    public static function fromChangeTable(string $name): self
    {
        return new self($name);
    }

    public static function fromRenameTable(string $oldName, string $newName): self
    {
        $table = new self($newName);

        $table->actions[] = new RenameTable($oldName, $newName);

        return $table;
    }

    public static function fromDropTable(string $name): self
    {
        $table = new self($name);

        $table->actions[] = new DropTable($name);

        return $table;
    }

    public function addColumn(string $name, Type $dataType): Column
    {
        $column = new Column($name, $dataType);

        $this->actions[] = new AddColumn($this->name, $column);

        return $column;
    }

    public function dropColumn(string $name): void
    {
        $this->actions[] = new DropColumn($this->name, $name);
    }

    public function renameColumn(string $oldName, string $newName): void
    {
        $this->actions[] = new RenameColumn($this->name, $oldName, $newName);
    }

    public function changeColumn(string $name, Type $dataType): Column
    {
        $column = new Column($name, $dataType);

        $this->actions[] = new ChangeColumn($this->name, $column);

        return $column;
    }

    public function primaryKey(string $columnName, string ...$columnNames): void
    {
        $this->actions[] = new AddPrimaryKey(
            $this->name,
            new PrimaryKey(sprintf('%s_pkey', $this->name), $columnName, ...$columnNames),
        );
    }

    public function namedPrimaryKey(string $name, string $columnName, string ...$columnNames): void
    {
        $this->actions[] = new AddPrimaryKey(
            $this->name,
            new PrimaryKey($name, $columnName, ...$columnNames),
        );
    }

    public function dropPrimaryKey(?string $name = null): void
    {
        $this->actions[] = new DropPrimaryKey($this->name, $name ?? sprintf('%s_pkey', $this->name));
    }

    public function renamePrimaryKey(string $oldName, string $newName): void
    {
        $this->actions[] = new RenamePrimaryKey($this->name, $oldName, $newName);
    }

    public function addUniqueConstraint(string $name, string $columnName, string ...$columnNames): void
    {
        $this->actions[] = new AddUniqueConstraint($this->name, new Unique($name, $columnName, ...$columnNames));
    }

    public function dropUniqueConstraint(string $name): void
    {
        $this->actions[] = new DropUniqueConstraint($this->name, $name);
    }

    public function addIndex(string $name, string $column, string ...$columns): void
    {
        $this->actions[] = new AddIndex($this->name, new Index($name, $this->name, array_merge([$column], $columns)));
    }

    public function addBtreeIndex(string $name, string $column, string ...$columns): void
    {
        $this->actions[] = new AddIndex($this->name, new Index($name, $this->name, array_merge([$column], $columns), 'btree'));
    }

    public function addHashIndex(string $name, string $column, string ...$columns): void
    {
        $this->actions[] = new AddIndex($this->name, new Index($name, $this->name, array_merge([$column], $columns), 'hash'));
    }

    public function addGistIndex(string $name, string $column, string ...$columns): void
    {
        $this->actions[] = new AddIndex($this->name, new Index($name, $this->name, array_merge([$column], $columns), 'gist'));
    }

    public function addGinIndex(string $name, string $column, string ...$columns): void
    {
        $this->actions[] = new AddIndex($this->name, new Index($name, $this->name, array_merge([$column], $columns), 'gin'));
    }

    public function dropIndex(string $name): void
    {
        $this->actions[] = new DropIndex($this->name, $name);
    }

    public function addCheck(string $name, string $expression): void
    {
        $this->actions[] = new AddCheck($this->name, new Check($name, $expression));
    }

    public function dropCheck(string $name): void
    {
        $this->actions[] = new DropCheck($this->name, $name);
    }

    public function getActions(): TableActions
    {
        return new TableActions($this->name, ...$this->actions);
    }
}
