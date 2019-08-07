<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\DropCheck;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class DropCheckTest extends TestCase
{
    public function testGetName(): void
    {
        $action = new DropCheck(new Label('table_name'), new Label('check_name'));

        $this->assertSame('check_name', $action->getName()->toString());
    }

    public function testGetQueries(): void
    {
        $queries = (new DropCheck(new Label('table_name'), new Label('check_name')))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" DROP CONSTRAINT "check_name"',
            $queries[0],
        );
    }
}
