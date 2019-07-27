<?php declare(strict_types=1);

namespace PeeHaa\Migres\Cli;

use League\CLImate\CLImate;

final class Flag
{
    private CLImate $climate;

    private string $flag;

    private string $text;

    private ?string $extraText;

    public function __construct(CLImate $climate, string $flag, string $text, ?string $extraText = null)
    {
        $this->climate   = $climate;
        $this->flag      = $flag;
        $this->text      = $text;
        $this->extraText = $extraText;
    }

    public function render(): void
    {
        $this->climate->info('  ' . $this->flag);
        $this->climate->br();
        $this->climate->white('    ' . $this->text);

        if ($this->extraText !== null) {
            $this->climate->br();
            $this->climate->out('    ' . $this->extraText);
        }

        $this->climate->br();
        $this->climate->br();
    }
}
