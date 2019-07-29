<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\DropUniqueConstraint;
use PHPUnit\Framework\TestCase;

class DropUniqueConstraintTest extends TestCase
{
    public function testGetName(): void
    {
        $action = new DropUniqueConstraint('table_name', 'column_name_unique');

        $this->assertSame('column_name_unique', $action->getName());
    }

    public function testGetQueries(): void
    {
        $queries = (new DropUniqueConstraint('table_name', 'column_name_unique'))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" DROP CONSTRAINT "column_name_unique"',
            $queries[0],
        );
    }
}
