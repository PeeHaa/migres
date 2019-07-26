<?php declare(strict_types=1);

namespace PeeHaa\Migres\Migration\Table;

use PeeHaa\Migres\Migration\Actions;

final class Create implements Migration
{
    private string $tableName;

    private Actions $actions;

    public function __construct(string $tableName, Actions $actions)
    {
        $this->tableName = $tableName;
        $this->actions   = $actions;
    }

    /**
     * @internal
     */
    public function getActions(): Actions
    {
        return $this->actions;
    }
}
