<?php

namespace WrongWare\EBNFParser;

class Token
{
    protected $type;
    protected $value;

    public function __construct(string $type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->type.':'.$this->value;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getValue()
    {
        return $this->value;
    }
}
