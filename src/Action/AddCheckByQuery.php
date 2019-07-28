<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Migration\Queries;

final class AddCheckByQuery extends TableAction implements Action
{
    private string $name;

    private string $expression;

    public function __construct(string $tableName, string $name, string $expression)
    {
        parent::__construct($tableName);

        $this->name       = $name;
        $this->expression = $expression;
    }

    public function toQueries(): Queries
    {
        return new Queries(
            sprintf('ALTER TABLE "%s" ADD CONSTRAINT "%s" CHECK (%s)', $this->tableName, $this->name, $this->expression),
        );
    }
}
