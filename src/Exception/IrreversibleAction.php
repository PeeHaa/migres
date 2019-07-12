<?php declare(strict_types=1);

namespace PeeHaa\Migres\Exception;

class IrreversibleAction extends Exception
{
    public function __construct(string $action)
    {
        parent::__construct(sprintf('`%s` action can not be reversed.', $action));
    }
}
