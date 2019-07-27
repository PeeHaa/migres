<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\MigrationSpecification;

class CreatePrimaryKeyInCreateTable extends MigrationSpecification
{
    public function change(): void
    {
        $this->table('create_primary_key_in_create_table')
            ->addColumn('primary_key', 'bigserial')
            ->addPrimaryKey('pk_create_primary_key_in_create_table', 'primary_key')
            ->removePrimaryKey('pk_create_primary_key_in_create_table')
            ->create()
        ;
    }
}
