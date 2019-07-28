<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\MigrationSpecification;
use PeeHaa\Migres\Specification\Table;

class AllTableActions extends MigrationSpecification
{
    public function change(): void
    {
        $this->createTable('create_table', function (Table $table) {});
        $this->renameTable('create_table', 'rename_table');
        $this->dropTable('rename_table');
    }
}
