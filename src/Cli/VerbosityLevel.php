<?php declare(strict_types=1);

namespace PeeHaa\Migres\Cli;

final class VerbosityLevel
{
    public const VERBOSITY_LEVEL_0 = 0;
    public const VERBOSITY_LEVEL_1 = 1;
    public const VERBOSITY_LEVEL_2 = 2;
    public const VERBOSITY_LEVEL_3 = 3;

    private int $level;

    public function __construct(int $level = self::VERBOSITY_LEVEL_1)
    {
        if ($level < 0 || $level > 3) {
            throw new \TypeError(sprintf('Argument 1 passed to %s must be one of: 0, 1, 2 or 3', __METHOD__));
        }

        $this->level = $level;
    }

    /**
     * @param array<int,string> $arguments
     */
    public static function fromCliArguments(array $arguments): self
    {
        foreach (array_reverse($arguments) as $argument) {
            if (!preg_match('~^-v{1,3}$~', $argument)) {
                continue;
            }

            return new self(substr_count($argument, 'v'));
        }

        return new self();
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function hasLevel(int $level): bool
    {
        return $this->level >= $level;
    }
}
