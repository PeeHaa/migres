<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\MigrationSpecification;

class RenameTableTest extends MigrationSpecification
{
    public function change(): void
    {
        $this->createTable('to_be_renamed', function () {});

        $this->renameTable('to_be_renamed', 'rename_table_test');
    }
}
