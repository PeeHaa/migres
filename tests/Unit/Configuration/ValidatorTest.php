<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Configuration;

use PeeHaa\Migres\Configuration\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public function testValidateReturnsFalseWhenFileCouldNotBeFound(): void
    {
        $this->assertFalse((new Validator(DATA_DIRECTORY . '/config/invalid.php'))->validate());
    }

    public function testValidateReturnsFalseWhenConfigurationMissesTheMigrationPathKey(): void
    {
        $this->assertFalse((new Validator(DATA_DIRECTORY . '/config/missing-migration-path.php'))->validate());
    }

    public function testValidateReturnsFalseWhenConfigurationMissesTheNamespaceKey(): void
    {
        $this->assertFalse((new Validator(DATA_DIRECTORY . '/config/missing-namespace.php'))->validate());
    }

    public function testValidateReturnsFalseWhenConfigurationMissesTheDatabaseKey(): void
    {
        $this->assertFalse((new Validator(DATA_DIRECTORY . '/config/missing-database.php'))->validate());
    }

    public function testValidateReturnsTrueWhenValid(): void
    {
        $this->assertTrue((new Validator(DATA_DIRECTORY . '/config/valid.php'))->validate());
    }
}
