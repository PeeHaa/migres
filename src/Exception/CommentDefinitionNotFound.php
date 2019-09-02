<?php declare(strict_types=1);

namespace PeeHaa\Migres\Exception;

class CommentDefinitionNotFound extends Retrospection
{
    public function __construct(string $tableName)
    {
        parent::__construct(
            sprintf('Could not find definition of comment for table `%s`.', $tableName),
        );
    }
}
