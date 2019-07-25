<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Migration\Queries;

class ReverseAction
{
    private string $tableName;

    private Action $action;

    public function __construct(string $tableName, Action $action)
    {
        $this->tableName = $tableName;
        $this->action    = $action;
    }

    public function toQueries(): Queries
    {
        return $this->action->toQueries($this->tableName);
    }
}
