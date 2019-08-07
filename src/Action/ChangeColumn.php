<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Migration\Queries;
use PeeHaa\Migres\Specification\Column;
use PeeHaa\Migres\Specification\Label;

final class ChangeColumn extends TableAction implements Action
{
    private Column $column;

    public function __construct(Label $tableName, Column $column)
    {
        parent::__construct($tableName);

        $this->column = $column;
    }

    public function getName(): Label
    {
        return $this->column->getName();
    }

    public function toQueries(): Queries
    {
        $queries = [];

        $queries[] = sprintf(
            'ALTER TABLE "%s" ALTER COLUMN "%s" TYPE %s',
            $this->tableName->toString(),
            $this->column->getName()->toString(),
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
            return sprintf(
                'ALTER TABLE "%s" ALTER COLUMN "%s" DROP DEFAULT',
                $this->tableName->toString(),
                $this->column->getName()->toString(),
            );
        }

        return sprintf(
            'ALTER TABLE "%s" ALTER COLUMN "%s" SET DEFAULT %s',
            $this->tableName->toString(),
            $this->column->getName()->toString(),
            $options->getDefaultValue($this->column),
        );
    }

    public function getSetNullQuery(): string
    {
        $options = $this->column->getOptions();

        if ($options->isNullable()) {
            return sprintf(
                'ALTER TABLE "%s" ALTER COLUMN "%s" DROP NOT NULL',
                $this->tableName->toString(),
                $this->column->getName()->toString(),
            );
        }

        return sprintf(
            'ALTER TABLE "%s" ALTER COLUMN "%s" SET NOT NULL',
            $this->tableName->toString(),
            $this->column->getName()->toString(),
        );
    }
}
