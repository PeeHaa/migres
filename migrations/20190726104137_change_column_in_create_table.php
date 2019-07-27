<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\MigrationSpecification;

class ChangeColumnInCreateTable extends MigrationSpecification
{
    public function change(): void
    {
        $this->table('change_column_in_create_table')
            ->addColumn('change_type', 'integer')
            ->changeColumn('change_type', 'bigint')
            ->addColumn('change_to_not_null', 'integer')
            ->changeColumn('change_to_not_null', 'integer', ['null' => false])
            ->addColumn('change_to_null', 'integer', ['null' => false])
            ->changeColumn('change_to_null', 'integer')
            ->addColumn('change_to_default', 'integer')
            ->changeColumn('change_to_default', 'integer', ['default' => 42])
            ->create()
        ;
    }
}
