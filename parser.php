<?php

namespace WrongWare\EBNFParser;

class Parser
{
    protected $stream;
    protected $syntax;
    protected $future = [];
/*
    protected $rules = [
        '<terms>' => '<term> <operator> <terms>',
        '<term>' => 'T_KEY T_SEPARATOR T_VALUE',
        '<operator>' => 'T_WHITESPACE T_OPERATOR T_WHITESPACE',
    ];
*/
    public function __construct(TokenStream $stream)
    {
        $this->stream = $stream;
        $this->syntax = new Group();
    }

    public function parse()
    {
        while (($token = $this->next()) && $token->getType() != 'EOS') {
            switch ($token->getType()) {
                case 'T_OPENPAREN':
                    $this->expect('T_KEY');
                    $this->expectFuture('T_CLOSEPAREN');
                    $this->syntax = new Group('parenthesis', $this->syntax);
                    break;
                case 'T_CLOSEPAREN':
                    $this->matchFuture('T_CLOSEPAREN');
                    $this->expectOr(['T_WHITESPACE', 'EOS']);
                    $group = $this->syntax;
                    $this->syntax = $this->syntax->getPrev();
                    $group->setPrev(null);
                    $this->syntax->add($group);
                    break;
                case 'T_WHITESPACE':
                    $this->expect('T_OPERATOR');
                    $operator = $this->next();
                    $this->expect('T_WHITESPACE');
                    $this->next();
                    $this->syntax->add(new Operator($operator->getValue()));
                    break;
                case 'T_KEY':
                    $key = $token->getValue();
                    $this->expect('T_SEPARATOR');
                    $this->next();
                    $this->expect('T_VALUE');
                    $value = $this->next();
                    $this->syntax->add(new Term($key, $value->getValue()));
                    break;
                default:
                    throw new \Exception('Syntax error. Unexpected token '.$token);
                    break;
            }
        }

        if (!empty($this->future)) {
            throw new \Exception('Syntax error. Missing tokens '.implode(',', $this->future));
        }

        return $this->syntax;
    }

    protected function expect(string $expectedType)
    {
        $type = $this->stream->lookahead()->getType();

        if ($expectedType != $this->stream->lookahead()->getType()) {
            throw new \Exception("Syntax error. Expected {$expectedType}, got {$type}");
        }
    }

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

    protected function expectFuture(string $expectedType)
    {
        array_push($this->future, $expectedType);
    }

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

    protected function next()
    {
        return $this->stream->next();
    }
}
