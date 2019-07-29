<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit;

use PeeHaa\Migres\MigrationSpecification;
use PeeHaa\Migres\Specification\Table;
use PHPUnit\Framework\TestCase;

class MigrationSpecificationTest extends TestCase
{
    public function testCreateTable(): void
    {
        $specification = new class extends MigrationSpecification
        {
            public function change(): void
            {
                $this->createTable('table_name', function (Table $table) {
                });
            }
        };

        $specification->change();

        $this->assertCount(1, $specification->getMigrationSteps());
    }

    public function testChangeTable(): void
    {
        $specification = new class extends MigrationSpecification
        {
            public function change(): void
            {
                $this->changeTable('table_name', function (Table $table) {
                });
            }
        };

        $specification->change();

        $this->assertCount(1, $specification->getMigrationSteps());
    }

    public function testRenameTable(): void
    {
        $specification = new class extends MigrationSpecification
        {
            public function change(): void
            {
                $this->renameTable('old_name', 'new_table');
            }
        };

        $specification->change();

        $this->assertCount(1, $specification->getMigrationSteps());
    }

    public function testDropTable(): void
    {
        $specification = new class extends MigrationSpecification
        {
            public function change(): void
            {
                $this->dropTable('table_name');
            }
        };

        $specification->change();

        $this->assertCount(1, $specification->getMigrationSteps());
    }
}
