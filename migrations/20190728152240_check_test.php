<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\DataType\IntegerType;
use PeeHaa\Migres\MigrationSpecification;
use PeeHaa\Migres\Specification\Table;

class CheckTest extends MigrationSpecification
{
    public function change(): void
    {
        $this->createTable('checkTest', function (Table $table) {
            $table->addColumn('smaller_than_10', new IntegerType());
            $table->addCheck('smaller_than_10', 'smaller_than_10 < 10');
            $table->dropCheck('smaller_than_10');
        });
    }
}
