<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Constraint\CombinedPrimaryKey;
use PeeHaa\Migres\Migration\Queries;

final class AddPrimaryKey implements Action
{
    private CombinedPrimaryKey $primaryKey;

    public function __construct(CombinedPrimaryKey $primaryKey)
    {
        $this->primaryKey = $primaryKey;
    }

    public function getCombinedPrimaryKey(): CombinedPrimaryKey
    {
        return $this->primaryKey;
    }

    public function toQueries(string $tableName): Queries
    {
        return new Queries(sprintf('ALTER TABLE "%s" ADD %s', $tableName, $this->primaryKey->toSql()));
    }
}
