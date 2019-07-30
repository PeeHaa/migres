<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Data\TestMigrations;

use PeeHaa\Migres\DataType\BigSerial;
use PeeHaa\Migres\MigrationSpecification;
use PeeHaa\Migres\Specification\Table;

class MigrationTestWithInvalidFilenames extends MigrationSpecification
{
    public function change(): void
    {
        $this->createTable('create_table', function (Table $table) {
            $table->addColumn('id', new BigSerial());
        });
    }
}
