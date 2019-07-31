<?php declare(strict_types=1);

namespace PeeHaa\Migres\Constraint;

final class ForeignKey extends NamedConstraint implements Constraint
{
    /** @var array<string> */
    private array $columns;

    private ?string $referencedTable;

    /** @var array<string> */
    private ?array $referencedColumns;

    private string $onDelete = 'NO ACTION';

    private string $onUpdate = 'NO ACTION';

    public function __construct(string $name, string ...$columns)
    {
        $this->columns = $columns;

        parent::__construct($name);
    }

    public function references(string $referenceTableName, string $column, string ...$columns): self
    {
        $this->referencedTable   = $referenceTableName;
        $this->referencedColumns = array_merge([$column], $columns);

        return $this;
    }

    public function onDeleteCascade(): self
    {
        $this->onDelete = 'CASCADE';

        return $this;
    }

    public function onDeleteRestrict(): self
    {
        $this->onDelete = 'RESTRICT';

        return $this;
    }

    public function onDeleteNoAction(): self
    {
        $this->onDelete = 'NO ACTION';

        return $this;
    }

    public function onUpdateCascade(): self
    {
        $this->onUpdate = 'CASCADE';

        return $this;
    }

    public function onUpdateRestrict(): self
    {
        $this->onUpdate = 'RESTRICT';

        return $this;
    }

    public function onUpdateNoAction(): self
    {
        $this->onUpdate = 'NO ACTION';

        return $this;
    }

    public function toSql(): string
    {
        $columns = array_reduce($this->columns, static function (array $columns, string $name): array {
            $columns[] = sprintf('"%s"', $name);

            return $columns;
        }, []);

        $referencedColumns = array_reduce($this->referencedColumns, static function (array $referencedColumns, string $name): array {
            $referencedColumns[] = sprintf('"%s"', $name);

            return $referencedColumns;
        }, []);

        return sprintf(
            'CONSTRAINT "%s" FOREIGN KEY (%s) REFERENCES %s (%s) ON DELETE %s ON UPDATE %s',
            $this->name,
            implode(', ', $columns),
            $this->referencedTable,
            implode(', ', $referencedColumns),
            $this->onDelete,
            $this->onUpdate,
        );
    }
}
