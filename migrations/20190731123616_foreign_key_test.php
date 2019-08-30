<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\DataType\BigInt;
use PeeHaa\Migres\DataType\BigSerial;
use PeeHaa\Migres\MigrationSpecification;
use PeeHaa\Migres\Specification\Table;

class ForeignKeyTest extends MigrationSpecification
{
    public function change(): void
    {
        $this->createTable('foreign_key_test_1', function (Table $table) {
            $table->addColumn('id', new BigSerial());
            $table->primaryKey('id');
        });

        $this->createTable('foreign_key_test_2', function (Table $table) {
            $table->addColumn('id', new BigSerial());
            $table->addColumn('foreign_id', new BigInt())->notNull();
            $table->primaryKey('id');
            $table->addForeignKey('foreign_key_test_2_foreign_key_test_1_fkey', ['foreign_id'], 'foreign_key_test_1', ['id']);
            $table->dropForeignKey('foreign_key_test_2_foreign_key_test_1_fkey');
        });
    }
}
