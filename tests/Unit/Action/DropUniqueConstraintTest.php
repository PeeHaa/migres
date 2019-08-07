<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\DropUniqueConstraint;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class DropUniqueConstraintTest extends TestCase
{
    public function testGetName(): void
    {
        $action = new DropUniqueConstraint(new Label('table_name'), new Label('column_name_unique'));

        $this->assertSame('column_name_unique', $action->getName()->toString());
    }

    public function testGetQueries(): void
    {
        $queries = (new DropUniqueConstraint(new Label('table_name'), new Label('column_name_unique')))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" DROP CONSTRAINT "column_name_unique"',
            $queries[0],
        );
    }
}
