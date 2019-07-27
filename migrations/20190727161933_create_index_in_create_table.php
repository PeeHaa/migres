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
            ->addIndex('test1', ['indexed_unique1'], ['unique' => true])
            ->removeCheck('test1')
            ->addIndex('test2', ['indexed_unique1', 'indexed_unique2'], ['unique' => true])
            ->removeCheck('test2')
            ->addColumn('indexed1', 'integer')
            ->addColumn('indexed2', 'integer')
            ->addIndex('test3', ['indexed1 desc', 'indexed2 desc'], ['method' => 'btree'])
            ->removeIndex('test3')
            ->create()
        ;
    }
}
