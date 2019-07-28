<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Migration\Queries;
use PeeHaa\Migres\Specification\Column;

final class ChangeColumn extends TableAction implements Action
{
    private Column $column;

    public function __construct(string $tableName, Column $column)
    {
        parent::__construct($tableName);

        $this->column = $column;
    }

    public function getName(): string
    {
        return $this->column->getName();
    }

    public function toQueries(): Queries
    {
        $queries = [];

        $queries[] = sprintf(
            'ALTER TABLE "%s" ALTER COLUMN "%s" TYPE %s',
            $this->tableName,
            $this->column->getName(),
            $this->column->getType()->toSql(),
        );

        $queries[] = $this->getSetDefaultQuery();
        $queries[] = $this->getSetNullQuery();

        return new Queries(...$queries);
    }

    private function getSetDefaultQuery(): string
    {
        $options = $this->column->getOptions();

        if (!$options->hasDefault()) {
            return sprintf('ALTER TABLE "%s" ALTER COLUMN "%s" DROP DEFAULT', $this->tableName, $this->column->getName());
        }

        return sprintf(
            'ALTER TABLE "%s" ALTER COLUMN "%s" SET DEFAULT %s',
            $this->tableName,
            $this->column->getName(),
            $options->getDefaultValue($this->column),
        );
    }

    public function getSetNullQuery(): string
    {
        $options = $this->column->getOptions();

        if ($options->isNullable()) {
            return sprintf('ALTER TABLE "%s" ALTER COLUMN "%s" DROP NOT NULL', $this->tableName, $this->column->getName());
        }

        return sprintf('ALTER TABLE "%s" ALTER COLUMN "%s" SET NOT NULL', $this->tableName, $this->column->getName());
    }
}
