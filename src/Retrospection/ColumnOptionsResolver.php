<?php declare(strict_types=1);

namespace PeeHaa\Migres\Retrospection;

use PeeHaa\Migres\Specification\ColumnOptions;
use PeeHaa\Migres\Constraint\NotNull;
use PeeHaa\Migres\DataType\BigSerial;
use PeeHaa\Migres\DataType\Type;

final class ColumnOptionsResolver
{
    private Sequence $sequence;

    public function __construct(Sequence $sequence)
    {
        $this->sequence = $sequence;
    }

    public function resolve(Type $internalType, ColumnInformation $columnInformation): ColumnOptions
    {
        $columnOptions = new ColumnOptions();

        if (!$internalType instanceof BigSerial && !$columnInformation->getColumnDefinition()->isNullable()) {
            $columnOptions->notNull();
        }

        if ($this->hasDefaultValue($columnInformation)) {
            $columnOptions->setDefault($this->getDefaultValue($columnInformation));
        }

        return $columnOptions;
    }

    private function hasDefaultValue(ColumnInformation $columnInformation): bool
    {
        if ($columnInformation->getColumnDefinition()->getDataType() === 'bigint' && $this->sequence->isColumnUsingSequence($columnInformation)) {
            return false;
        }

        if ($columnInformation->getColumnDefinition()->getDataType() === 'integer' && $this->sequence->isColumnUsingSequence($columnInformation)) {
            return false;
        }

        if ($columnInformation->getColumnDefinition()->getDataType() === 'smallint' && $this->sequence->isColumnUsingSequence($columnInformation)) {
            return false;
        }

        return $columnInformation->getColumnDefinition()->getDefaultValue() !== null;
    }

    /**
     * @return mixed
     */
    private function getDefaultValue(ColumnInformation $columnInformation)
    {
        return $columnInformation->getColumnDefinition()->getDefaultValue();
    }
}
