<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Fakes;

use League\CLImate\CLImate as ClimateImpl;

/**
 * This class just makes it a bit more convenient to add spies to CLImate as we do not depend on magic calls
 */
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

    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function darkGray(string $content): self
    {
        return $this;
    }

    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function white(string $content): self
    {
        return $this;
    }

    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function out(string $content): self
    {
        return $this;
    }

    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function lightGreen(string $content): self
    {
        return $this;
    }

    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function error(string $content): self
    {
        return $this;
    }
}
