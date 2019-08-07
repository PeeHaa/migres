<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\AddUniqueConstraint;
use PeeHaa\Migres\Constraint\Unique;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class AddUniqueConstraintTest extends TestCase
{
    public function testGetCheck(): void
    {
        $constraint = new Unique(new Label('column1_unique'), new Label('column1'));

        $action = new AddUniqueConstraint(new Label('table_name'), $constraint);

        $this->assertSame($constraint, $action->getConstraint());
    }

    public function testToQueries(): void
    {
        $constraint = new Unique(new Label('column1_unique'), new Label('column1'));

        $queries = (new AddUniqueConstraint(new Label('table_name'), $constraint))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" ADD CONSTRAINT "column1_unique" UNIQUE ("column1")',
            $queries[0],
        );
    }

    public function testToQueriesWithMultipleColumns(): void
    {
        $constraint = new Unique(new Label('column1_unique'), new Label('column1'), new Label('column2'));

        $queries = (new AddUniqueConstraint(new Label('table_name'), $constraint))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" ADD CONSTRAINT "column1_unique" UNIQUE ("column1", "column2")',
            $queries[0],
        );
    }
}
