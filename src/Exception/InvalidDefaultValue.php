<?php declare(strict_types=1);

namespace PeeHaa\Migres\Exception;

class InvalidDefaultValue extends Exception
{
    /**
     * @param mixed $defaultValue
     */
    public function __construct($defaultValue)
    {
        parent::__construct(sprintf('Default value of type `%s` is not valid.', $this->getType($defaultValue)));
    }

    /**
     * @param mixed $defaultValue
     */
    private function getType($defaultValue): string
    {
        if (gettype($defaultValue) !== 'object') {
            return gettype($defaultValue);
        }

        return get_class($defaultValue);
    }
}
