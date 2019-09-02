<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Specification;

use PeeHaa\Migres\Action\AddTableComment;
use PeeHaa\Migres\Action\RemoveTableComment;
use PeeHaa\Migres\Specification\Label;
use PeeHaa\Migres\Specification\TableOptions;
use PHPUnit\Framework\TestCase;

class TableOptionsTest extends TestCase
{
    public function testComment(): void
    {
        $options = new TableOptions(new Label('table_name'));

        $options->comment('The comment');

        $this->assertCount(1, $options->getActions());

        $this->assertInstanceOf(AddTableComment::class, $options->getActions()[0]);
    }

    public function testRemoveComment(): void
    {
        $options = new TableOptions(new Label('table_name'));

        $options->removeComment();

        $this->assertCount(1, $options->getActions());

        $this->assertInstanceOf(RemoveTableComment::class, $options->getActions()[0]);
    }

    public function testNoCommentAction(): void
    {
        $options = new TableOptions(new Label('table_name'));

        $this->assertCount(0, $options->getActions());
    }

    public function testRemoveCommentOverridesComment(): void
    {
        $options = new TableOptions(new Label('table_name'));

        $options->comment('The comment');
        $options->removeComment();

        $this->assertCount(1, $options->getActions());

        $this->assertInstanceOf(RemoveTableComment::class, $options->getActions()[0]);
    }

    public function testCommentOverridesRemoveComment(): void
    {
        $options = new TableOptions(new Label('table_name'));

        $options->removeComment();
        $options->comment('The comment');

        $this->assertCount(1, $options->getActions());

        $this->assertInstanceOf(AddTableComment::class, $options->getActions()[0]);
    }
}
