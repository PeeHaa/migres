<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\DataType\BigInt;
use PeeHaa\Migres\DataType\BigSerial;
use PeeHaa\Migres\MigrationSpecification;
use PeeHaa\Migres\Specification\Table;

class ForeignKeyMultipleColumnTest extends MigrationSpecification
{
    public function change(): void
    {
        $this->createTable('foreign_key_multiple_1', function (Table $table) {
            $table->addColumn('id1', new BigSerial());
            $table->addColumn('id2', new BigSerial());
            $table->primaryKey('id1');
            $table->addUniqueConstraint('id2_unq', 'id1', 'id2');
        });

        $this->createTable('foreign__key_multiple_2', function (Table $table) {
            $table->addColumn('id', new BigSerial());
            $table->addColumn('foreign_id', new BigInt())->notNull();
            $table->primaryKey('id');
            $table->addForeignKey('multiple_fkey', 'id', 'foreign_id')
                ->references('foreign_key_multiple_1', 'id1', 'id2')
            ;
        });
    }
}
