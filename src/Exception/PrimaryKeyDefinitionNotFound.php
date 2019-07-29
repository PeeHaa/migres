<?php declare(strict_types=1);

namespace PeeHaa\Migres\Exception;

class PrimaryKeyDefinitionNotFound extends Retrospection
{
    public function __construct(string $tableName, string $keyName)
    {
        parent::__construct(
            sprintf('Could not find definition of primary key `%s` in table `%s`.', $keyName, $tableName),
        );
    }
}
