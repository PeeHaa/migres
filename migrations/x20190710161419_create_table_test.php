<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\MigrationSpecification;

class CreateTableTest extends MigrationSpecification
{
    public function change(): void
    {
        $this->table('migration_test')
            ->addColumn('id', 'bigserial')
            //->addColumn('varchar_col', 'character varying(255)')
            //->addColumn('varchar_col_not_null', 'character varying(255)', ['null' => false])
            //->addColumn('varchar_col_with_default', 'character varying(255)', ['default' => 'This is the default'])
            //->addColumn('varchar_col_with_custom_length', 'character varying(24)')
            //->addColumn('json_col', 'jsonb')
            ->addColumn('column_to_be_removed', 'integer')
            ->removeColumn('column_to_be_removed')
            //->addColumn('column_with_unique_constraint', 'integer')
            //->addIndex('unq_migration_test_column_with_unique_constraint', ['column_with_unique_constraint'], ['unique' => true])
            //->addColumn('column_to_be_renamed', 'varchar(128)')
            //->renameColumn('column_to_be_renamed', 'column_renamed')
            //->addColumn('first_column_index', 'integer')
            //->addColumn('second_column_index', 'integer')
            //->addIndex('idx_custom_index', ['first_column_index desc', 'second_column_index desc'], ['method' => 'btree'])
            //->addColumn('column_to_be_changed', 'integer')
            //->changeColumn('column_to_be_changed', 'bigint', ['null' => false, 'default' => 1200])
            //->addColumn('column_with_check', 'integer')
            //->addCheck('chk_greater_than_10', 'column_with_check > 10')
            //->addPrimaryKey('key_migration_test', 'id')
            //->addColumn('column_to_be_removed_in_previous_migration', 'integer')
            ->create()
        ;
    }
}
