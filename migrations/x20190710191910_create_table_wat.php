<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\MigrationSpecification;

class CreateTableWat extends MigrationSpecification
{
    public function change(): void
    {
        $this->table('wat')
            ->create()
        ;
    }
}
