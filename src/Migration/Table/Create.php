<?php declare(strict_types=1);

namespace PeeHaa\Migres\Migration\Table;

use PeeHaa\Migres\Action\DropTable;
use PeeHaa\Migres\Migration\Actions;
use PeeHaa\Migres\Migration\Migrations;

final class Create implements Migration
{
    private string $tableName;

    private Actions $actions;

    public function __construct(string $tableName, Actions $actions)
    {
        $this->tableName = $tableName;
        $this->actions   = $actions;
    }

    public function getMigrationActions(): array
    {
        return [
            'up'   => $this->actions,
            'down' => $this->down(),
        ];
    }

    /**
     * @internal
     */
    public function up(): Actions
    {
        return $this->actions;
    }

    /**
     * @internal
     */
    public function down(): Actions
    {
        return new Actions($this->tableName, new DropTable());
    }
}
