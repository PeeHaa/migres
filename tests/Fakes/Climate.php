<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Fakes;

use League\CLImate\CLImate as ClimateImpl;

class Climate extends ClimateImpl
{
    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function info(string $content): self
    {
        return $this;
    }

    public function br(): self
    {
        return $this;
    }
}