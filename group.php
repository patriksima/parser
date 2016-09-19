<?php

namespace WrongWare\EBNFParser;

class Group
{
    protected $prev;
    protected $type;
    protected $terms = [];

    public function __construct(string $type = '', Group $prev = null)
    {
        $this->type = $type;
        $this->prev = $prev;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getPrev()
    {
        return $this->prev;
    }

    public function setPrev($prev)
    {
        $this->prev = $prev;
    }

    public function add($term)
    {
        $this->terms[] = $term;
    }
}
