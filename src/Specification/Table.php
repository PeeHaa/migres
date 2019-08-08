<?php declare(strict_types=1);

namespace PeeHaa\Migres\Specification;

use PeeHaa\Migres\Action\Action;
use PeeHaa\Migres\Action\AddCheck;
use PeeHaa\Migres\Action\AddColumn;
use PeeHaa\Migres\Action\AddForeignKey;
use PeeHaa\Migres\Action\AddIndex;
use PeeHaa\Migres\Action\AddPrimaryKey;
use PeeHaa\Migres\Action\AddUniqueConstraint;
use PeeHaa\Migres\Action\ChangeColumn;
use PeeHaa\Migres\Action\CreateTable;
use PeeHaa\Migres\Action\DropCheck;
use PeeHaa\Migres\Action\DropColumn;
use PeeHaa\Migres\Action\DropForeignKey;
use PeeHaa\Migres\Action\DropIndex;
use PeeHaa\Migres\Action\DropPrimaryKey;
use PeeHaa\Migres\Action\DropTable;
use PeeHaa\Migres\Action\DropUniqueConstraint;
use PeeHaa\Migres\Action\RenameColumn;
use PeeHaa\Migres\Action\RenamePrimaryKey;
use PeeHaa\Migres\Action\RenameTable;
use PeeHaa\Migres\Constraint\Check;
use PeeHaa\Migres\Constraint\ForeignKey;
use PeeHaa\Migres\Constraint\Index;
use PeeHaa\Migres\Constraint\PrimaryKey;
use PeeHaa\Migres\Constraint\Unique;
use PeeHaa\Migres\DataType\Type;
use PeeHaa\Migres\Migration\TableActions;

final class Table
{
    private Label $name;

    /** @var array<Action> */
    private array $actions = [];

    private function __construct(Label $name)
    {
        $this->name = $name;
    }

    public static function fromCreateTable(Label $name): self
    {
        $table = new self($name);

        $table->actions[] = new CreateTable($name);

        return $table;
    }

    public static function fromChangeTable(Label $name): self
    {
        return new self($name);
    }

    public static function fromRenameTable(Label $oldName, Label $newName): self
    {
        $table = new self($newName);

        $table->actions[] = new RenameTable($oldName, $newName);

        return $table;
    }

    public static function fromDropTable(Label $name): self
    {
        $table = new self($name);

        $table->actions[] = new DropTable($name);

        return $table;
    }

    public function addColumn(string $name, Type $dataType): Column
    {
        $column = new Column(new Label($name), $dataType);

        $this->actions[] = new AddColumn($this->name, $column);

        return $column;
    }

    public function dropColumn(string $name): void
    {
        $this->actions[] = new DropColumn($this->name, new Label($name));
    }

    public function renameColumn(string $oldName, string $newName): void
    {
        $this->actions[] = new RenameColumn($this->name, new Label($oldName), new Label($newName));
    }

    public function changeColumn(string $name, Type $dataType): Column
    {
        $column = new Column(new Label($name), $dataType);

        $this->actions[] = new ChangeColumn($this->name, $column);

        return $column;
    }

    public function primaryKey(string $columnName, string ...$columnNames): void
    {
        $this->actions[] = new AddPrimaryKey(
            $this->name,
            new PrimaryKey(
                new Label(sprintf('%s_pkey', $this->name->toString())),
                new Label($columnName),
                ...array_map(fn (string $column) => new Label($column), $columnNames),
            ),
        );
    }

    public function namedPrimaryKey(string $name, string $columnName, string ...$columnNames): void
    {
        $this->actions[] = new AddPrimaryKey(
            $this->name,
            new PrimaryKey(
                new Label($name),
                new Label($columnName),
                ...array_map(fn (string $column) => new Label($column), $columnNames),
            ),
        );
    }

    public function dropPrimaryKey(?string $name = null): void
    {
        $this->actions[] = new DropPrimaryKey(
            $this->name,
            new Label($name ?? sprintf('%s_pkey', $this->name->toString())),
        );
    }

    public function renamePrimaryKey(string $oldName, string $newName): void
    {
        $this->actions[] = new RenamePrimaryKey($this->name, new Label($oldName), new Label($newName));
    }

    public function addUniqueConstraint(string $name, string $columnName, string ...$columnNames): void
    {
        $this->actions[] = new AddUniqueConstraint($this->name, new Unique(
            new Label($name),
            new Label($columnName),
            ...array_map(fn (string $columnName) => new Label($columnName), $columnNames)));
    }

    public function dropUniqueConstraint(string $name): void
    {
        $this->actions[] = new DropUniqueConstraint($this->name, new Label($name));
    }

    public function addIndex(string $name, string $column, string ...$columns): void
    {
        $this->actions[] = new AddIndex(
            $this->name,
            new Index(
                new Label($name),
                $this->name,
                array_map(fn (string $column) => new Label($column), array_merge([$column], $columns)),
            ),
        );
    }

    public function addBtreeIndex(string $name, string $column, string ...$columns): void
    {
        $this->actions[] = new AddIndex(
            $this->name,
            new Index(
                new Label($name),
                $this->name,
                array_map(fn (string $column) => new Label($column), array_merge([$column], $columns)),
                'btree',
            ),
        );
    }

    public function addHashIndex(string $name, string $column, string ...$columns): void
    {
        $this->actions[] = new AddIndex(
            $this->name,
            new Index(
                new Label($name),
                $this->name,
                array_map(fn (string $column) => new Label($column), array_merge([$column], $columns)),
                'hash',
            ),
        );
    }

    public function addGistIndex(string $name, string $column, string ...$columns): void
    {
        $this->actions[] = new AddIndex(
            $this->name,
            new Index(
                new Label($name),
                $this->name,
                array_map(fn (string $column) => new Label($column), array_merge([$column], $columns)),
                'gist',
            ),
        );
    }

    public function addGinIndex(string $name, string $column, string ...$columns): void
    {
        $this->actions[] = new AddIndex(
            $this->name,
            new Index(
                new Label($name),
                $this->name,
                array_map(fn (string $column) => new Label($column), array_merge([$column], $columns)),
                'gin',
            ),
        );
    }

    public function dropIndex(string $name): void
    {
        $this->actions[] = new DropIndex($this->name, new Label($name));
    }

    public function addCheck(string $name, string $expression): void
    {
        $this->actions[] = new AddCheck($this->name, new Check(new Label($name), $expression));
    }

    public function dropCheck(string $name): void
    {
        $this->actions[] = new DropCheck($this->name, new Label($name));
    }

    public function addForeignKey(string $name, string $column, string ...$columns): ForeignKey
    {
        $foreignKey = new ForeignKey(
            new Label($name),
            ...array_map(fn (string $column) => new Label($column), array_merge([$column], $columns)),
        );

        $this->actions[] = new AddForeignKey($this->name, $foreignKey);

        return $foreignKey;
    }

    public function dropForeignKey(string $name): void
    {
        $this->actions[] = new DropForeignKey($this->name, new Label($name));
    }

    public function getActions(): TableActions
    {
        return new TableActions($this->name->toString(), ...$this->actions);
    }
}
