<?php

namespace WrongWare\EBNFParser;

class Operator
{
    protected $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }
}
