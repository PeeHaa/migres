<?php declare(strict_types=1);

namespace PeeHaa\Migres\Exception;

class LabelTooLong extends Exception
{
    public function __construct(string $label)
    {
        parent::__construct(sprintf('Label (`%s`) is longer than the maximum label length of 63.', $label));
    }
}
