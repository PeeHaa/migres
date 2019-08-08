<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\DataType\BigSerial;
use PeeHaa\Migres\MigrationSpecification;
use PeeHaa\Migres\Specification\Table;

class TestConstraintRefector extends MigrationSpecification
{
    public function change(): void
    {
        $this->createTable('drop_primary_key_refactor', function (Table $table) {
            $table->addColumn('id', new BigSerial());
            $table->primaryKey('id');
            $table->dropPrimaryKey();
        });
    }
}
