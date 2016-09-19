<?php

namespace WrongWare\EBNFParser;

class TokenStream
{
    protected $tokens = [];
    protected $position = -1;

    /**
     * Constructor.
     *
     * @param array of Token
     */
    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * Return token at current position + your position.
     * Don't increment internal pointer.
     *
     * @param int $position
     *
     * @return Token
     */
    public function lookahead($position = 1)
    {
        return $this->tokens[$this->position + $position];
    }

    /**
     * Return token at current position and increment pointer.
     *
     * @return Token
     */
    public function next()
    {
        $this->position = $this->position + 1;

        return $this->tokens[$this->position];
    }
}
