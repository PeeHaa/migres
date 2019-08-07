<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\AddCheck;
use PeeHaa\Migres\Constraint\Check;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class AddCheckTest extends TestCase
{
    public function testGetCheck(): void
    {
        $check = new Check(new Label('column_bigger_than_10'), 'column > 10');

        $action = new AddCheck(new Label('table_name'), $check);

        $this->assertSame($check, $action->getCheck());
    }

    public function testToQueries(): void
    {
        $queries = (new AddCheck(new Label('table_name'), new Check(new Label('column_bigger_than_10'), 'column > 10')))
            ->toQueries()
        ;

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" ADD CONSTRAINT "column_bigger_than_10" CHECK (column > 10)',
            $queries[0],
        );
    }
}
