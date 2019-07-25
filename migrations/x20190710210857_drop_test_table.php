<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\MigrationSpecification;

class DropTestTable extends MigrationSpecification
{
    public function change(): void
    {
        $this->table('migration_test_renamed_again')
            ->drop()
            ->change()
        ;
    }
}
