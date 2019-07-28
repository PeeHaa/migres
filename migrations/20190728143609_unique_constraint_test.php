<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\DataType\IntegerType;
use PeeHaa\Migres\MigrationSpecification;
use PeeHaa\Migres\Specification\Table;

class UniqueConstraintTest extends MigrationSpecification
{
    public function change(): void
    {
        $this->createTable('unique_constraint_test', function (Table $table) {
            $table->addColumn('column1', new IntegerType());
            $table->addColumn('column2', new IntegerType());
            $table->addUniqueConstraint('columns_1_and_2_unique', 'column1', 'column2');
            $table->dropUniqueConstraint('columns_1_and_2_unique');
        });
    }
}
