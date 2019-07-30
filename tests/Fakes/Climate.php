<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Fakes;

use League\CLImate\CLImate as ClimateImpl;

/**
 * This class just makes it a bit more convenient to add spies to CLImate as we do not depend on magic calls
 */
class Climate extends ClimateImpl
{
    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function info(string $content): \League\CLImate\CLImate
    {
        return $this;
    }

    public function br(): \League\CLImate\CLImate
    {
        return $this;
    }

    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function darkGray(string $content): \League\CLImate\CLImate
    {
        return $this;
    }

    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function white(string $content): \League\CLImate\CLImate
    {
        return $this;
    }

    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function out(string $content): \League\CLImate\CLImate
    {
        return $this;
    }

    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function lightGreen(string $content): \League\CLImate\CLImate
    {
        return $this;
    }

    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function error(string $content): \League\CLImate\CLImate
    {
        return $this;
    }

    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function input(string $content): \League\CLImate\CLImate
    {
        return $this;
    }

    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function password(string $content): \League\CLImate\CLImate
    {
        return $this;
    }

    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function defaultTo(string $content): \League\CLImate\CLImate
    {
        return $this;
    }

    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function accept(array $accept): \League\CLImate\CLImate
    {
        return $this;
    }

    /**
     * @return mixed
     */
    public function prompt()
    {
        return '';
    }
}
