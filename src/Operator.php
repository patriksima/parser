<?php

namespace WrongWare\SearchParser;

/**
 * Class Operator.
 */
class Operator
{
    /**
     * Type of operator.
     *
     * @var string
     */
    protected $type;

    /**
     * Constructor.
     *
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * Getter.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
