<?php declare(strict_types=1);

namespace PeeHaa\Migres\Exception;

class InvalidDataTypeSpecification extends Exception
{
    public function __construct(string $specification, string $type)
    {
        parent::__construct(sprintf('Invalid data type specification (`%s`) for type %s', $specification, $type));
    }
}
