<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\MigrationSpecification;

class CreateCombinedPrimaryKeyInCreateTable extends MigrationSpecification
{
    public function change(): void
    {
        $this->table('create_combined_primary_key_in_create_table')
            ->addColumn('primary_key1', 'bigserial')
            ->addColumn('primary_key2', 'integer')
            ->addColumn('primary_key3', 'integer')
            ->addPrimaryKey('pk', 'primary_key1', 'primary_key2', 'primary_key3')
            ->removePrimaryKey('pk')
            ->create()
        ;
    }
}
