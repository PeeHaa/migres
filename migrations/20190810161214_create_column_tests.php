<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\DataType\BigSerial;
use PeeHaa\Migres\MigrationSpecification;
use PeeHaa\Migres\Specification\Table;

class CreateColumnTests extends MigrationSpecification
{
    public function change(): void
    {
        $this->createTable('column_comment', function (Table $table) {
            $table->addColumn('id', new BigSerial())->comment('This is the comment!');
        });

        $this->changeTable('column_comment', function (Table $table) {
            $table->addColumn('id', new BigSerial())->removeComment();
        });
    }
}
