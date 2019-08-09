<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\RemoveTableComment;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class RemoveTableCommentTest extends TestCase
{
    public function testToQueries(): void
    {
        $queries = (new RemoveTableComment(new Label('table_name')))
            ->toQueries()
        ;

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'COMMENT ON TABLE "table_name" IS NULL',
            $queries[0],
        );
    }
}
