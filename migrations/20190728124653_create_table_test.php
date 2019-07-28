<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\MigrationSpecification;
use PeeHaa\Migres\Specification\Table;

class CreateTableTest extends MigrationSpecification
{
    public function change(): void
    {
        $this->createTable('create_table_test', function (Table $table) {});
    }
}
