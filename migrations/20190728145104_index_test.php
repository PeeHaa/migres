<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\DataType\IntegerType;
use PeeHaa\Migres\DataType\JsonB;
use PeeHaa\Migres\DataType\Point;
use PeeHaa\Migres\MigrationSpecification;
use PeeHaa\Migres\Specification\Table;

class IndexTest extends MigrationSpecification
{
    public function change(): void
    {
        $this->createTable('index_test', function (Table $table) {
            $table->addColumn('default_index1', new IntegerType());
            $table->addColumn('default_index2', new IntegerType());
            $table->addIndex('default_index_idx', 'default_index1');
            $table->dropIndex('default_index_idx');
            $table->addIndex('default_index_idx', 'default_index1', 'default_index2 DESC');
            $table->addBtreeIndex('btree_index_idx', 'default_index1');
            $table->addHashIndex('hash_index_idx', 'default_index1');
            $table->addColumn('point_index', new Point());
            $table->addGistIndex('gist_index_idx', 'point_index');
            $table->addColumn('json_index', new JsonB());
            $table->addGinIndex('gin_index_idx', 'json_index');
        });
    }
}
