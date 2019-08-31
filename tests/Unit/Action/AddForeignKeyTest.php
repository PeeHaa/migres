<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\AddForeignKey;
use PeeHaa\Migres\Constraint\ForeignKey;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class AddForeignKeyTest extends TestCase
{
    public function testGetForeignKey(): void
    {
        $foreignKey = new ForeignKey(new Label('name_fkey'), [new Label('reference_id')], new Label('reference_table'), [new Label('id')]);

        $action = new AddForeignKey(new Label('table_name'), $foreignKey);

        $this->assertSame($foreignKey, $action->getForeignKey());
    }

    public function testToQueries(): void
    {
        $foreignKey = new ForeignKey(new Label('name_fkey'), [new Label('reference_id')], new Label('reference_table'), [new Label('id')]);

        $queries = (new AddForeignKey(new Label('table_name'), $foreignKey))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" ADD CONSTRAINT "name_fkey" FOREIGN KEY ("reference_id") REFERENCES reference_table ("id") ON DELETE NO ACTION ON UPDATE NO ACTION',
            $queries[0],
        );
    }
}
