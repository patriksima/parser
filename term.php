<?php

namespace WrongWare\EBNFParser;

class Term
{
    protected $key;
    protected $value;

    public function __construct(string $key, string $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->key.':'.$this->value;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getValue()
    {
        return $this->value;
    }
}
