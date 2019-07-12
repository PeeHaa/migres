<?php declare(strict_types=1);

namespace PeeHaa\Migres\Examples\Migrations;

use PeeHaa\Migres\MigrationSpecification;

class RenameTestTable extends MigrationSpecification
{
    public function change(): void
    {
        $this->table('migration_test')
            ->addColumn('before_rename', 'integer')
            ->rename('migration_test_renamed')
            ->addColumn('after_rename', 'integer')
            ->rename('migration_test_renamed_again')
            ->change()
        ;
    }
}
