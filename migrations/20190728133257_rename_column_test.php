<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\DataType\IntegerType;
use PeeHaa\Migres\MigrationSpecification;
use PeeHaa\Migres\Specification\Table;

class RenameColumnTest extends MigrationSpecification
{
    public function change(): void
    {
        $this->createTable('rename_column_test', function (Table $table) {
            $table->addColumn('to_be_renamed', new IntegerType());
            $table->renameColumn('to_be_renamed', 'renamed');
        });
    }
}
