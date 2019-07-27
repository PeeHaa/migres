<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\MigrationSpecification;

class RenameTableInCreateTable extends MigrationSpecification
{
    public function change(): void
    {
        $this->table('rename_table_in_create_table')
            ->addColumn('column1', 'integer')
            ->rename('renamed_table_in_create_table1')
            ->rename('renamed_table_in_create_table2')
            ->rename('renamed_table_in_create_table3')
            ->rename('renamed_table_in_create_table4')
            ->addColumn('column2', 'integer')
            ->create()
        ;
    }
}
