<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\AddUniqueConstraint;
use PeeHaa\Migres\Constraint\Unique;
use PHPUnit\Framework\TestCase;

class AddUniqueConstraintTest extends TestCase
{
    public function testGetCheck(): void
    {
        $constraint = new Unique('column1_unique', 'column1');

        $action = new AddUniqueConstraint('table_name', $constraint);

        $this->assertSame($constraint, $action->getConstraint());
    }

    public function testToQueries(): void
    {
        $constraint = new Unique('column1_unique', 'column1');

        $queries = (new AddUniqueConstraint('table_name', $constraint))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" ADD CONSTRAINT "column1_unique" UNIQUE ("column1")',
            $queries[0],
        );
    }

    public function testToQueriesWithMultipleColumns(): void
    {
        $constraint = new Unique('column1_unique', 'column1', 'column2');

        $queries = (new AddUniqueConstraint('table_name', $constraint))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" ADD CONSTRAINT "column1_unique" UNIQUE ("column1", "column2")',
            $queries[0],
        );
    }
}
