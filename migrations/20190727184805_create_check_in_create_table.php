<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\MigrationSpecification;

class CreateCheckInCreateTable extends MigrationSpecification
{
    public function change(): void
    {
        $this->table('create_check_in_create_table')
            ->addColumn('check', 'integer')
            ->addCheck('bigger_than_10', '"check" > 10')
            ->create()
        ;
    }
}
