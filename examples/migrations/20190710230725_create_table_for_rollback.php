<?php declare(strict_types=1);

namespace PeeHaa\Migres\Examples\Migrations;

use PeeHaa\Migres\MigrationSpecification;

class CreateTableForRollback extends MigrationSpecification
{
    public function change(): void
    {
        $this->table('rollback_test')
            ->addColumn('column1', 'integer')
            ->create()
        ;
    }
}
