<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\MigrationSpecification;

class ChangeTableTest extends MigrationSpecification
{
    public function change(): void
    {
        $this->table('migration_test')
            ->addColumn('new_column_from_change', 'varchar(16)')
            ->removeColumn('column_to_be_removed_in_previous_migration')
            ->addColumn('column_to_be_removed', 'integer')
            ->renameColumn('column_to_be_removed', 'column_to_be_removed_renamed')
            ->removeColumn('column_to_be_removed')
            ->addColumn('column_with_unique_constraint_from_change', 'integer')
            ->addIndex('unq_migration_test_column_with_unique_constraint_from_change', ['column_with_unique_constraint_from_change'], ['unique' => true])
            ->addColumn('column_to_be_renamed', 'varchar(128)')
            //->renameColumn('column_to_be_renamed', 'column_renamed_from_change')
            ->addColumn('first_column_index_from_change', 'integer')
            ->addColumn('second_column_index_from_change', 'integer')
            ->addIndex('idx_custom_index_from_change', ['first_column_index_from_change desc', 'second_column_index_from_change desc'], ['method' => 'btree'])
            ->addColumn('column_to_be_changed_from_change', 'integer')
            //->changeColumn('column_to_be_changed_from_change', 'bigint', ['null' => false, 'default' => 1200])
            ->addColumn('column_with_check_from_change', 'integer')
            ->addCheck('chk_greater_than_10_from_change', 'column_with_check_from_change > 10')
            ->addColumn('column_with_index_to_be_removed_first', 'integer')
            ->addColumn('column_with_index_to_be_removed_second', 'integer')
            //->addIndex('idx_to_be_removed', ['column_with_index_to_be_removed_first', 'column_with_index_to_be_removed_second'])
            //->removeIndex('idx_to_be_removed')
            ->addColumn('column_with_check_to_be_deleted', 'integer')
            //->addCheck('chk_greater_than_100_to_be_deleted', 'column_with_check_to_be_deleted > 100')
            //->removeCheck('chk_greater_than_100_to_be_deleted')
            //->removePrimaryKey('key_migration_test')
            ->change()
        ;
    }
}
