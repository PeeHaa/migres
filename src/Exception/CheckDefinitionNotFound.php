<?php declare(strict_types=1);

namespace PeeHaa\Migres\Exception;

class CheckDefinitionNotFound extends Retrospection
{
    public function __construct(string $tableName, string $keyName)
    {
        parent::__construct(
            sprintf('Could not find definition of check `%s` in table `%s`.', $keyName, $tableName)
        );
    }
}
