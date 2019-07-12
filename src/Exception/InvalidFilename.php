<?php declare(strict_types=1);

namespace PeeHaa\Migres\Exception;

class InvalidFilename extends \Exception
{
    public function __construct(string $filename)
    {
        parent::__construct(sprintf('Invalid filename (`%s`) for a migration file.', $filename));
    }
}
