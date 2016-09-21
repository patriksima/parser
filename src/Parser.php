<?php

namespace WrongWare\SearchParser;

/**
 * Simple parser for search string.
 *
 * eg. (key:value or key:value and key:value)
 */
class Parser
{
    /**
     * Result of Lexer.
     *
     * @var TokenStream
     */
    protected $stream;

    /**
     * Result of parsing.
     *
     * @var array
     */
    protected $syntax = [];

    /**
     * Expected tokens stack for special cases
     * for example matching parenthesis.
     *
     * @var array
     */
    protected $future = [];

    /**
     * Processing stack.
     *
     * @var array
     */
    protected $stack = [];

    /**
     * Constructor.
     *
     * @param TokenStream $stream
     */
    public function __construct(TokenStream $stream)
    {
        $this->stream = $stream;
    }

    /**
     * Main parsing loop.
     *
     * @throws Exception if syntax error
     *
     * @return Group
     */
    public function parse()
    {
        $this->stack = $this->loop();
        $this->syntax = $this->valuation($this->stack);

        return $this->syntax;
    }

    /**
     * Recursive parsing of token stream and building
     * stack of tokens with polish notation.
     *
     * @return array
     */
    protected function loop()
    {
        $stack = [];

        while (($token = $this->next()) && $token->getType() != 'EOS') {
            switch ($token->getType()) {
                case 'T_OPENPAREN':
                    $term = $this->parseParenthesis($token);
                    $stack[] = $term;
                    break;
                case 'T_CLOSEPAREN':
                    $this->matchFuture('T_CLOSEPAREN');
                    $this->expectOr(['T_WHITESPACE', 'EOS']);

                    return $stack;
                case 'T_WHITESPACE':
                    $term = $this->parseOperator($token);
                    $prev = array_pop($stack);
                    $stack[] = $term;
                    $stack[] = $prev;
                    break;
                case 'T_KEY':
                    $term = $this->parseTerm($token);
                    $stack[] = $term;
                    break;
                default:
                    throw new \Exception('Syntax error. Unexpected token '.$token);
                    break;
            }
        }

        if (!empty($this->future)) {
            throw new \Exception('Syntax error. Missing tokens '.implode(',', $this->future));
        }

        return $stack;
    }

    /**
     * Recursive valuation of stack.
     *
     * @param array $stack stack with polish notation
     *
     * @return array
     */
    protected function valuation(array $stack)
    {
        $syntax = [];

        $stack = array_reverse($stack);

        while ($item = array_pop($stack)) {
            if ($item instanceof \WrongWare\SearchParser\Operator) {
                $group = new Group($item->getType());
                $arg1 = array_pop($stack);
                $arg2 = array_pop($stack);
                if (is_array($arg1)) {
                    $arg1 = $this->valuation($arg1);
                }
                if (is_array($arg2)) {
                    $arg2 = $this->valuation($arg2);
                }
                if ($arg2 instanceof \WrongWare\SearchParser\Operator) {
                    $arg2 = $this->valuation([$arg2, array_pop($stack), array_pop($stack)]);
                }
                $group->add($arg1);
                $group->add($arg2);
                $syntax[] = $group;
            }
            if ($item instanceof \WrongWare\SearchParser\Term) {
                $syntax[] = $item;
            }
        }

        return $syntax;
    }

    /**
     * Parse key:value expression.
     *
     * @param Token $token
     *
     * @return Term
     */
    protected function parseTerm(Token $token)
    {
        if ($token->getType() != 'T_KEY') {
            return false;
        }

        $key = $token->getValue();
        $this->expect('T_SEPARATOR');
        $this->next();
        $this->expect('T_VALUE');
        $value = $this->next()->getValue();

        return new Term($key, $value);
    }

    /**
     * Parse operator or/and.
     *
     * @param Token $token
     *
     * @return Term
     */
    protected function parseOperator(Token $token)
    {
        if ($token->getType() != 'T_WHITESPACE') {
            return false;
        }

        $this->expect('T_OPERATOR');
        $type = $this->next()->getValue();
        $this->expect('T_WHITESPACE');
        $this->next();

        return new Operator($type);
    }

    /**
     * Parse parenthesis.
     *
     * @param Token $token
     *
     * @return Term
     */
    protected function parseParenthesis(Token $token)
    {
        if ($token->getType() != 'T_OPENPAREN') {
            return false;
        }

        $this->expect('T_KEY');
        $this->expectFuture('T_CLOSEPAREN');

        return $this->loop();
    }

    /**
     * Check next token type.
     *
     * @param string $expectedType
     *
     * @throws Exception if token doesn't match with expected
     */
    protected function expect(string $expectedType)
    {
        $type = $this->stream->lookahead()->getType();

        if ($expectedType != $this->stream->lookahead()->getType()) {
            throw new \Exception("Syntax error. Expected {$expectedType}, got {$type}");
        }
    }

    /**
     * Check next token types in OR mode.
     *
     * @param array $expectedTypes
     *
     * @throws Exception if none token doesn't match with expected
     */
    protected function expectOr(array $expectedTypes)
    {
        foreach ($expectedTypes as $expectedType) {
            $type = $this->stream->lookahead()->getType();

            if ($expectedType == $this->stream->lookahead()->getType()) {
                return;
            }
        }
        throw new \Exception("Syntax error. Expected {implode(',',$expectedTypes)}, got {$type}");
    }

    /**
     * Put expected type of token on stack.
     *
     * @param string $expectedType
     */
    protected function expectFuture(string $expectedType)
    {
        array_push($this->future, $expectedType);
    }

    /**
     * Check stack against expected type of token.
     *
     * @param string $expectedType
     *
     * @throws Exception if expected token missing or doesn't match
     */
    protected function matchFuture(string $expectedType)
    {
        if (empty($this->future)) {
            throw new \Exception('Syntax error. Missing tokens '.$expectedType);
        }

        $type = array_pop($this->future);

        if ($expectedType != $type) {
            throw new \Exception("Syntax error. Expected {$expectedType}, got {$type}");
        }
    }

    /**
     * Get next token from token stream.
     *
     * @return Token
     */
    protected function next()
    {
        return $this->stream->next();
    }
}
