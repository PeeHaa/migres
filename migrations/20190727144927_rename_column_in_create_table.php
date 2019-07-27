<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\MigrationSpecification;

class RenameColumnInCreateTable extends MigrationSpecification
{
    public function change(): void
    {
        $this->table('rename_column_in_create_table')
            ->addColumn('rename', 'integer')
            ->changeColumn('rename', 'integer', ['null' => false])
            ->renameColumn('rename', 'renamed')
            ->changeColumn('renamed', 'integer', ['null' => false, 'default' => 42])
            ->create()
        ;
    }
}
