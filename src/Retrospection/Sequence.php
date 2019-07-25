<?php declare(strict_types=1);

namespace PeeHaa\Migres\Retrospection;

final class Sequence
{
    public function isColumnUsingSequence(ColumnInformation $columnInformation): bool
    {
        return $columnInformation->getColumnDefinition()->getDefaultValue() === $this->getSerialSequenceValue($columnInformation);
    }

    private function getSerialSequenceValue(ColumnInformation $columnInformation): string
    {
        return sprintf(
            "nextval('%s_%s_seq'::regclass)",
            $columnInformation->getTableName(),
            $columnInformation->getColumnName()
        );
    }
}
