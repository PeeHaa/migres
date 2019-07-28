<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action_old;

use PeeHaa\Migres\Constraint\PrimaryKey;
use PeeHaa\Migres\Migration\Queries;

final class AddPrimaryKey implements Action
{
    private PrimaryKey $primaryKey;

    public function __construct(PrimaryKey $primaryKey)
    {
        $this->primaryKey = $primaryKey;
    }

    public function getCombinedPrimaryKey(): PrimaryKey
    {
        return $this->primaryKey;
    }

    public function toQueries(string $tableName): Queries
    {
        return new Queries(sprintf('ALTER TABLE "%s" ADD %s', $tableName, $this->primaryKey->toSql()));
    }
}
