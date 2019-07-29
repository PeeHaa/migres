<?php declare(strict_types=1);

namespace PeeHaa\Migres\Command;

use League\CLImate\CLImate;

final class SetUp implements Command
{
    private CLImate $climate;

    private array $settings = [
        'migrationPath' => null,
        'namespace'     => null,
        'database'      => [
            'name'     => null,
            'host'     => null,
            'port'     => null,
            'username' => null,
            'password' => null,
        ],
    ];

    public function __construct(CLImate $climate)
    {
        $this->climate = $climate;
    }

    public function run(): void
    {
        $this->climate->br();

        $this->climate->info('Migres - Configuration wizard');

        $this->climate->br();

        $this->climate->info('Set up migrations');

        $this->climate->br();

        $this->settings['migrationPath'] = $this->askForMigrationPath();
        $this->settings['namespace']     = $this->askForNamespace();

        $this->climate->br();

        $this->climate->info('Set up database connection');

        $this->climate->br();

        $this->settings['database']['name']     = $this->askForDatabaseName();
        $this->settings['database']['host']     = $this->askForDatabaseHost();
        $this->settings['database']['port']     = $this->askForDatabasePort();
        $this->settings['database']['username'] = $this->askForDatabaseUsername();
        $this->settings['database']['password'] = $this->askForDatabasePassword();

        $this->verifySettings();
    }

    private function askForMigrationPath(): string
    {
        $defaultDirectory = getcwd() . DIRECTORY_SEPARATOR . 'migrations';

        return $this->climate
            ->input(sprintf('What directory do you want to store your migrations in? [%s]', $defaultDirectory))
            ->defaultTo($defaultDirectory)
            ->prompt()
        ;
    }

    private function askForNamespace(): string
    {
        $defaultNamespace = 'Migres\Migrations';

        return $this->climate
            ->input(sprintf('What namespace do you want to use for your migrations? [%s]', $defaultNamespace))
            ->defaultTo($defaultNamespace)
            ->prompt()
        ;
    }

    private function askForDatabaseName(): string
    {
        $response = $this->climate
            ->input('What is the name of the database you want to run migrations on?')
            ->prompt()
        ;

        if (!$response) {
            return $this->askForDatabaseName();
        }

        return $response;
    }

    private function askForDatabaseHost(): string
    {
        return $this->climate
            ->input('What is the host address of your database? [localhost]')
            ->defaultTo('localhost')
            ->prompt()
        ;
    }

    private function askForDatabasePort(): int
    {
        $response = $this->climate
            ->input('What is the name of the database you want to run migrations on? [5432]')
            ->defaultTo('5432')
            ->prompt()
        ;

        if (!ctype_digit($response)) {
            return $this->askForDatabasePort();
        }

        return (int) $response;
    }

    private function askForDatabaseUsername(): string
    {
        $response = $this->climate
            ->input('What is the username for the database?')
            ->prompt()
        ;

        if (!$response) {
            return $this->askForDatabaseUsername();
        }

        return $response;
    }

    private function askForDatabasePassword(): string
    {
        $response = $this->climate
            ->password('What is the password for the database?')
            ->prompt()
        ;

        if ($response !== $this->askForDatabasePasswordAgain()) {
            return $this->askForDatabasePassword();
        }

        return $response;
    }

    private function askForDatabasePasswordAgain(): string
    {
        return $this->climate
            ->password('Repeat the password for the database?')
            ->prompt()
        ;
    }

    private function verifySettings(): void
    {
        $this->climate->br();

        $this->climate->info('Settings to be saved:');

        $this->climate->br();

        $this->climate->table([
            [
                '#'        => '1',
                'settings' => 'Migration path',
                'value'    => $this->settings['migrationPath'],
            ],
            [
                '#'        => '2',
                'settings' => 'Namespace',
                'value'    => $this->settings['namespace'],
            ],
            [
                '#'        => '3',
                'settings' => 'Database name',
                'value'    => $this->settings['database']['name'],
            ],
            [
                '#'        => '4',
                'settings' => 'Database host',
                'value'    => $this->settings['database']['host'],
            ],
            [
                '#'        => '5',
                'settings' => 'Database port',
                'value'    => $this->settings['database']['port'],
            ],
            [
                '#'        => '6',
                'settings' => 'Database username',
                'value'    => $this->settings['database']['username'],
            ],
            [
                '#'        => '7',
                'settings' => 'Database password',
                'value'    => '*****',
            ],
        ]);

        $this->climate->br();

        $response = $this->climate
            ->input('Type y to verify your settings or the number of the setting you want to change: [y]')
            ->accept(['y', '1', '2', '3', '4', '5', '6', '7'])
            ->defaultTo('y')
            ->prompt()
        ;

        switch ($response) {
            case '1':
                $this->settings['migrationPath'] = $this->askForMigrationPath();
                $this->verifySettings();
                break;

            case '2':
                $this->settings['namespace'] = $this->askForNamespace();
                $this->verifySettings();
                break;

            case '3':
                $this->settings['database']['name'] = $this->askForDatabaseName();
                $this->verifySettings();
                break;

            case '4':
                $this->settings['database']['host'] = $this->askForDatabaseHost();
                $this->verifySettings();
                break;

            case '5':
                $this->settings['database']['port'] = $this->askForDatabasePort();
                $this->verifySettings();
                break;

            case '6':
                $this->settings['database']['password'] = $this->askForDatabasePassword();
                $this->verifySettings();
                break;

            case 'y':
                $this->createMigrationDirectoryIfNotExists();
                $this->createConfigFile();
                $this->finish();
        }
    }

    private function createMigrationDirectoryIfNotExists(): void
    {
        if (is_dir($this->settings['migrationPath'])) {
            return;
        }

        $this->climate->br();

        $this->climate->info('Creating migration directory');

        mkdir($this->settings['migrationPath']);

        if (is_dir($this->settings['migrationPath'])) {
            return;
        }

        $this->climate->error('Could not create migration directory');
    }

    private function createConfigFile(): void
    {
        $this->climate->br();

        $this->climate->info(sprintf('Writing settings to file %s', getcwd() . '/migres.php'));

        $template = "<?php declare(strict_types=1);\n\nreturn " . var_export($this->settings, true) . ";\n";

        file_put_contents(getcwd() . '/migres.php', $template);
    }

    private function finish(): void
    {
        $this->climate->br();

        $this->climate->info('Initialization is finished');
    }
}
