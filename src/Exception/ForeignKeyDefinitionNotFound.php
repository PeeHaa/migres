<?php declare(strict_types=1);

namespace PeeHaa\Migres\Exception;

class ForeignKeyDefinitionNotFound extends Retrospection
{
    public function __construct(string $keyName)
    {
        parent::__construct(
            sprintf('Could not find definition of foreign key `%s`', $keyName),
        );
    }
}
