<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Exception;

use PeeHaa\Migres\Exception\IrreversibleAction;
use PHPUnit\Framework\TestCase;

class IrreversibleActionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $this->expectExceptionMessage('`UNSUPPORTED` action can not be reversed.');

        throw new IrreversibleAction('UNSUPPORTED');
    }
}
