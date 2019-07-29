<?php declare(strict_types=1);

namespace PeeHaa\Migres\Retrospection;

final class ColumnDefinition
{
    /** @var mixed */
    private $defaultValue;

    private bool $isNullable;

    private string $dataType;

    private ?int $maximumLength;

    private ?int $numericPrecision;

    private ?int $numericScale;

    /**
     * @param mixed $defaultValue
     */
    private function __construct(
        $defaultValue,
        bool $isNullable,
        string $dataType,
        ?int $maximumLength,
        ?int $numericPrecision,
        ?int $numericScale
    ) {
        $this->defaultValue     = $defaultValue;
        $this->isNullable       = $isNullable;
        $this->dataType         = $dataType;
        $this->maximumLength    = $maximumLength;
        $this->numericPrecision = $numericPrecision;
        $this->numericScale     = $numericScale;
    }

    /**
     * @param array<string,mixed> $record
     */
    public static function fromInformationSchemaRecord(array $record): self
    {
        return new self(
            $record['column_default'],
            $record['is_nullable'] === 'YES' ? true : false,
            $record['data_type'],
            $record['character_maximum_length'],
            $record['numeric_precision'],
            $record['numeric_scale'],
        );
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    public function isNullable(): bool
    {
        return $this->isNullable;
    }

    public function getDataType(): string
    {
        return $this->dataType;
    }

    public function getMaximumLength(): ?int
    {
        return $this->maximumLength;
    }

    public function getNumericPrecision(): ?int
    {
        return $this->numericPrecision;
    }

    public function getNumericScale(): ?int
    {
        return $this->numericScale;
    }
}
