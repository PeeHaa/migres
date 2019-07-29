<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Command;

use PeeHaa\Migres\Cli\Output;
use PeeHaa\Migres\Cli\VerbosityLevel;
use PeeHaa\Migres\Command\CreateNewMigration;
use PeeHaa\Migres\Configuration\Configuration;
use PeeHaa\MigresTest\Fakes\Climate;
use PHPUnit\Framework\TestCase;

class CreateNewMigrationTest extends TestCase
{
    public function testRun(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->expects($this->once())
            ->method('br')
        ;

        $climate
            ->expects($this->once())
            ->method('lightGreen')
            ->willReturnCallback(static function (string $message) use ($climate) {
                preg_match('~^Created new migration at (?P<filename>.*)~', $message, $matches);

                unlink($matches['filename']);

                return $climate;
            })
        ;

        $configuration = Configuration::fromArray([
            'migrationPath' => DATA_DIRECTORY . '/migrations',
            'namespace'     => 'Foo\Bar',
            'database'      => [
                'name'     => 'test_db',
                'host'     => 'localhost',
                'port'     => 5432,
                'username' => 'username',
                'password' => 'password',
            ],
        ]);

        $command = new CreateNewMigration(
            $configuration,
            new Output($climate, new VerbosityLevel()),
            'CreateSomeTable',
        );

        $command->run();
    }
}
