<?php declare(strict_types=1);

namespace PeeHaa\Migres\Specification;

use PeeHaa\Migres\Exception\LabelTooLong;

final class Label
{
    private string $name;

    public function __construct(string $name)
    {
        if (strlen($name) > 63) {
            throw new LabelTooLong($name);
        }

        $this->name = $name;
    }

    public function toString(): string
    {
        return $this->name;
    }
}
