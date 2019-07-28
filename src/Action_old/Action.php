<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action_old;

use PeeHaa\Migres\Migration\Queries;

interface Action
{
    public function toQueries(string $tableName): Queries;
}
