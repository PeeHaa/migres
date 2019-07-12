<?php declare(strict_types=1);

namespace PeeHaa\Migres\Exception;

class UnsupportedDataType extends Exception
{
    public function __construct(string $type, string $specification)
    {
        parent::__construct(sprintf('Unsupported data type (`%s`) from specification %s', $type, $specification));
    }
}
