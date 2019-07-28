<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\DataType\BigInt;
use PeeHaa\Migres\DataType\TimestampWithoutTimezone;
use PeeHaa\Migres\MigrationSpecification;
use PeeHaa\Migres\Specification\Table;

class ChangeTable extends MigrationSpecification
{
    public function change(): void
    {
        $this->changeTable('table_to_be_changed', function (Table $table) {
            $table->changeColumn('id', new BigInt());
            $table->addColumn('created_at', new TimestampWithoutTimezone())->notNull()->default('NOW()');
            $table->primaryKey('id');
        });
    }
}
