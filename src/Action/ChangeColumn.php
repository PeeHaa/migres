<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Column;
use PeeHaa\Migres\Migration\Queries;

final class ChangeColumn implements Action
{
    private Column $column;

    public function __construct(Column $column)
    {
        $this->column = $column;
    }

    public function getName(): string
    {
        return $this->column->getName();
    }

    public function toQueries(string $tableName): Queries
    {
        $queries = [];

        $queries[] = sprintf(
            'ALTER TABLE "%s" ALTER COLUMN "%s" TYPE %s',
            $tableName,
            $this->column->getName(),
            $this->column->getType()->toSql(),
        );

        $queries[] = $this->getSetDefaultQuery($tableName);
        $queries[] = $this->getSetNullQuery($tableName);

        return new Queries(...$queries);
    }

    private function getSetDefaultQuery(string $tableName): string
    {
        $options = $this->column->getOptions();

        if (!$options->hasDefault()) {
            return sprintf('ALTER TABLE "%s" ALTER COLUMN "%s" DROP DEFAULT', $tableName, $this->column->getName());
        }

        return sprintf(
            'ALTER TABLE "%s" ALTER COLUMN "%s" SET DEFAULT %s',
            $tableName,
            $this->column->getName(),
            $options->getDefaultValue($this->column),
        );
    }

    public function getSetNullQuery(string $tableName): string
    {
        $options = $this->column->getOptions();

        if (!$options->hasNotNullConstraints()) {
            return sprintf('ALTER TABLE "%s" ALTER COLUMN "%s" DROP NOT NULL', $tableName, $this->column->getName());
        }

        return sprintf('ALTER TABLE "%s" ALTER COLUMN "%s" SET NOT NULL', $tableName, $this->column->getName());
    }
}
