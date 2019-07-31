<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Constraint\ForeignKey;
use PeeHaa\Migres\Migration\Queries;

final class AddForeignKey extends TableAction implements Action
{
    private ForeignKey $foreignKey;

    public function __construct(string $tableName, ForeignKey $foreignKey)
    {
        parent::__construct($tableName);

        $this->foreignKey = $foreignKey;
    }

    public function getForeignKey(): ForeignKey
    {
        return $this->foreignKey;
    }

    public function toQueries(): Queries
    {
        return new Queries(sprintf('ALTER TABLE "%s" ADD %s', $this->tableName, $this->foreignKey->toSql()));
    }
}
