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
            ->create()
        ;
    }
}
