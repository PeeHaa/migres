<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\DataType\IntegerType;
use PeeHaa\Migres\MigrationSpecification;
use PeeHaa\Migres\Specification\Table;

class DropColumnTest extends MigrationSpecification
{
    public function change(): void
    {
        $this->createTable('drop_column_test', function (Table $table) {
            $table->addColumn('to_be_deleted', new IntegerType());
            $table->dropColumn('to_be_deleted');
        });
    }
}
