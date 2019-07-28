<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\MigrationSpecification;

class DropTableTest extends MigrationSpecification
{
    public function change(): void
    {
        $this->createTable('to_be_deleted', function ()  {});

        $this->dropTable('to_be_deleted');
    }
}
