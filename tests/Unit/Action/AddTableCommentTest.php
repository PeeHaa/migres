<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\AddTableComment;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class AddTableCommentTest extends TestCase
{
    public function testToQueries(): void
    {
        $queries = (new AddTableComment(new Label('table_name'), 'The comment'))
            ->toQueries()
        ;

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'COMMENT ON TABLE "table_name" IS \'The comment\'',
            $queries[0],
        );
    }
}
