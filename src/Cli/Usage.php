<?php declare(strict_types=1);

namespace PeeHaa\Migres\Cli;

use League\CLImate\CLImate;

final class Usage
{
    private CLImate $climate;

    private string $usageText;

    public function __construct(CLImate $climate, string $usageText)
    {
        $this->climate   = $climate;
        $this->usageText = $usageText;
    }

    public function render(): void
    {
        $this->climate->darkGray($this->usageText);
        $this->climate->br();
    }
}
