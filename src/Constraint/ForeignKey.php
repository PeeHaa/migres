<?php declare(strict_types=1);

namespace PeeHaa\Migres\Constraint;

use PeeHaa\Migres\Specification\Label;

final class ForeignKey extends NamedConstraint implements Constraint
{
    /** @var array<Label> */
    private array $columns;

    private Label $referencedTable;

    /** @var array<Label> */
    private ?array $referencedColumns;

    private string $onDelete = 'NO ACTION';

    private string $onUpdate = 'NO ACTION';

    /**
     * @param array<string> $columns
     * @param array<string> $referencedColumns
     */
    public function __construct(Label $name, array $columns, Label $referencedTableName, array $referencedColumns)
    {
        foreach ($columns as $column) {
            if (!$column instanceof Label) {
                throw new \TypeError($this->getTypeErrorMessage(2, $column));
            }

            $this->columns[] = $column;
        }

        foreach ($referencedColumns as $column) {
            if (!$column instanceof Label) {
                throw new \TypeError($this->getTypeErrorMessage(4, $column));
            }

            $this->referencedColumns[] = $column;
        }

        if (count($this->columns) !== count($this->referencedColumns)) {
            throw new \Exception('Column count in foreign key constraint must match referenced column count');
        }

        $this->referencedTable = $referencedTableName;

        parent::__construct($name);
    }

    /**
     * @param mixed $argument
     */
    private function getTypeErrorMessage(int $argumentNumber, $argument): string
    {
        $type = gettype($argument);

        if ($type === 'object') {
            $type = get_class($type);
        }

        return sprintf(
            'Uncaught TypeError: Argument %d passed to %s must be of an array of %s, %s given',
            $argumentNumber,
            __CLASS__ . '::__construct()',
            Label::class,
            $type,
        );
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
        $columns = array_reduce($this->columns, static function (array $columns, Label $name): array {
            $columns[] = sprintf('"%s"', $name->toString());

            return $columns;
        }, []);

        $referencedColumns = array_reduce($this->referencedColumns, static function (array $referencedColumns, Label $name): array {
            $referencedColumns[] = sprintf('"%s"', $name->toString());

            return $referencedColumns;
        }, []);

        return sprintf(
            'CONSTRAINT "%s" FOREIGN KEY (%s) REFERENCES %s (%s) ON DELETE %s ON UPDATE %s',
            $this->name->toString(),
            implode(', ', $columns),
            $this->referencedTable->toString(),
            implode(', ', $referencedColumns),
            $this->onDelete,
            $this->onUpdate,
        );
    }
}
