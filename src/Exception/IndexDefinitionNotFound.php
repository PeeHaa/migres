<?php declare(strict_types=1);

namespace PeeHaa\Migres\Exception;

class IndexDefinitionNotFound extends Retrospection
{
    public function __construct(string $tableName, string $keyName)
    {
        parent::__construct(
            sprintf('Could not find definition of index `%s` in table `%s`.', $keyName, $tableName)
        );
    }
}
