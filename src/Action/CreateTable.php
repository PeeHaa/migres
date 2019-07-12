<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Migration\Queries;

final class CreateTable implements Action
{
    public function toQueries(string $tableName): Queries
    {
        return new Queries(sprintf('CREATE TABLE "%s" ()', $tableName));
    }
}
