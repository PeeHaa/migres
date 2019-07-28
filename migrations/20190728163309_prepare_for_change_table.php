<?php declare(strict_types=1);

namespace Migres\Migrations;

use PeeHaa\Migres\DataType\Boolean;
use PeeHaa\Migres\DataType\CharacterVarying;
use PeeHaa\Migres\DataType\Serial;
use PeeHaa\Migres\MigrationSpecification;
use PeeHaa\Migres\Specification\Table;

class PrepareForChangeTable extends MigrationSpecification
{
    public function change(): void
    {
        $this->createTable('table_to_be_changed', function (Table $table) {
            $table->addColumn('id', new Serial());
            $table->addColumn('name', new CharacterVarying(255))->notNull();
            $table->addColumn('email_address', new CharacterVarying(128))->notNull();
            $table->addColumn('is_admin', new Boolean())->notNull()->default(false);
        });
    }
}
