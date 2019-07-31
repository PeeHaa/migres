<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\DataType\BigInt;
use PeeHaa\Migres\DataType\BigSerial;
use PeeHaa\Migres\MigrationSpecification;
use PeeHaa\Migres\Specification\Table;

class ForeignKeyWithOnDeleteAndOnUpdate extends MigrationSpecification
{
    public function change(): void
    {
        $this->createTable('foreign_key_on_1', function (Table $table) {
            $table->addColumn('id', new BigSerial());
            $table->primaryKey('id');
        });

        $this->createTable('foreign_key_on_2', function (Table $table) {
            $table->addColumn('id', new BigSerial());
            $table->addColumn('foreign_id', new BigInt())->notNull();
            $table->primaryKey('id');
            $table->addForeignKey('on_fkey', 'foreign_id')
                ->references('foreign_key_on_1', 'id')
                ->onDeleteCascade()
                ->onUpdateCascade()
            ;
            $table->dropForeignKey('on_fkey');
        });
    }
}
