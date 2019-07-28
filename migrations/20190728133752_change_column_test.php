<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\DataType\BigInt;
use PeeHaa\Migres\DataType\IntegerType;
use PeeHaa\Migres\MigrationSpecification;
use PeeHaa\Migres\Specification\Table;

class ChangeColumnTest extends MigrationSpecification
{
    public function change(): void
    {
        $this->createTable('change_column_test', function (Table $table) {
            $table->addColumn('disallow_nulls', new IntegerType());
            $table->changeColumn('disallow_nulls', new IntegerType())->notNull();
            $table->addColumn('set_default', new IntegerType());
            $table->changeColumn('set_default', new IntegerType())->default(42);
            $table->addColumn('change_type', new IntegerType());
            $table->changeColumn('change_type', new BigInt());
            $table->addColumn('change_all', new IntegerType());
            $table->changeColumn('change_all', new BigInt())->notNull()->default(42);
        });
    }
}
