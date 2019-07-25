<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\MigrationSpecification;

class AddColumnForRollback extends MigrationSpecification
{
    public function change(): void
    {
        $this->table('rollback_test')
            ->addColumn('id', 'bigserial')
            ->addColumn('column2', 'integer')
            ->addColumn('column_with_unique_constraint', 'integer')
            ->addIndex('unq_migration_test_column_with_unique_constraint', ['column_with_unique_constraint'], ['unique' => true])
            ->addColumn('first_column_index', 'integer')
            ->addColumn('second_column_index', 'integer')
            ->addIndex('idx_custom_index', ['first_column_index desc', 'second_column_index desc'], ['method' => 'btree'])
            ->addColumn('column_with_check', 'integer')
            ->addCheck('chk_greater_than_10', 'column_with_check > 10')
            ->addPrimaryKey('key_migration_test', 'id')
            ->addColumn('column_to_be_removed_in_the_same_migration', 'integer')
            ->removeColumn('column_to_be_removed_in_the_same_migration')
            ->addColumn('column_to_be_renamed', 'integer')
            ->renameColumn('column_to_be_renamed', 'column_renamed')
            ->change()
        ;
    }
}
