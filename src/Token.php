<?php

namespace WrongWare\SearchParser;

/**
 * Term for Lexer.
 */
class Token
{
    /**
     * Type of token.
     *
     * @var string
     */
    protected $type;

    /**
     * Real value.
     *
     * @var string
     */
    protected $value;

    /**
     * Constructor.
     *
     * @param string $type
     * @param string $value
     */
    public function __construct(string $type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * Return string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->type.':'.$this->value;
    }

    /**
     * Getter for type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Getter for value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
