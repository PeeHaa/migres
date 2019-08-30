<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\DataType\BigSerial;
use PeeHaa\Migres\MigrationSpecification;
use PeeHaa\Migres\Specification\Table;

class Test2ConstraintRefector extends MigrationSpecification
{
    public function change(): void
    {
        $this->createTable('drop_primary_key_refactor2', function (Table $table) {
            $table->addColumn('id', new BigSerial());
            $table->namedPrimaryKey('named_pk', 'id');
            $table->dropPrimaryKey('named_pk');
        });

        $this->createTable('drop_unique_constraint', function (Table $table) {
            $table->addColumn('id', new BigSerial());
            $table->addUniqueConstraint('named_unique', 'id');
            $table->dropUniqueConstraint('named_unique');
        });

        $this->createTable('fkey_link', function (Table $table) {
            $table->addColumn('id', new BigSerial());
            $table->primaryKey('id');
        });

        $this->createTable('drop_reference', function (Table $table) {
            $table->addColumn('id', new BigSerial());
            $table->addForeignKey('named_fkey', ['id'], 'fkey_link', ['id']);
            $table->dropForeignKey('named_fkey');
        });
    }
}
