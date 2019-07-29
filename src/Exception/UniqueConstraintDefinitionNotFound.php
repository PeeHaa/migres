<?php declare(strict_types=1);

namespace PeeHaa\Migres\Exception;

class UniqueConstraintDefinitionNotFound extends Retrospection
{
    public function __construct(string $tableName, string $keyName)
    {
        parent::__construct(
            sprintf('Could not find definition of unique constraint `%s` in table `%s`.', $keyName, $tableName),
        );
    }
}
