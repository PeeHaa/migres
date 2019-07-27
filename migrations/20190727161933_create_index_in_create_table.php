<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\MigrationSpecification;

class CreateIndexInCreateTable extends MigrationSpecification
{
    public function change(): void
    {
        $this->table('create_index_in_create_table')
            ->addColumn('indexed_unique1', 'integer')
            ->addColumn('indexed_unique2', 'integer')
            ->addIndex('unq_create_index_in_create_table_indexed_unique1', ['indexed_unique1'], ['unique' => true])
            ->addIndex('unq_create_index_in_create_table_indexed_unique1_indexed_unique2', ['indexed_unique1', 'indexed_unique2'], ['unique' => true])
            ->addColumn('indexed1', 'integer')
            ->addColumn('indexed2', 'integer')
            ->addIndex('idx_create_index_in_create_table_indexed1_indexed2', ['indexed1 desc', 'indexed2 desc'], ['method' => 'btree'])
            ->create()
        ;
    }
}
