<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\DataType\BigSerial;
use PeeHaa\Migres\MigrationSpecification;
use PeeHaa\Migres\Specification\Table;

class CreatePrimaryKeyTest extends MigrationSpecification
{
    public function change(): void
    {
        $this->createTable('create_primary_key_test', function (Table $table) {
            $table->addColumn('id1', new BigSerial());
            $table->addColumn('id2', new BigSerial());
            $table->primaryKey('id1');
            $table->dropPrimaryKey();
            $table->namedPrimaryKey('custom_name', 'id1', 'id2');
            $table->dropPrimaryKey('custom_name');
            $table->namedPrimaryKey('to_be_renamed', 'id1', 'id2');
            $table->renamePrimaryKey('to_be_renamed', 'renamed');
        });
    }
}
