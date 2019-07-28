<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Constraint\PrimaryKey;
use PeeHaa\Migres\Migration\Queries;

final class AddPrimaryKey extends TableAction implements Action
{
    private PrimaryKey $primaryKey;

    public function __construct(string $tableName, PrimaryKey $primaryKey)
    {
        parent::__construct($tableName);

        $this->primaryKey = $primaryKey;
    }

    public function getPrimaryKey(): PrimaryKey
    {
        return $this->primaryKey;
    }

    public function toQueries(): Queries
    {
        return new Queries(sprintf('ALTER TABLE "%s" ADD %s', $this->tableName, $this->primaryKey->toSql()));
    }
}
