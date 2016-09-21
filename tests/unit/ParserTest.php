<?php

use WrongWare\SearchParser\Lexer;
use WrongWare\SearchParser\Parser;

class ParserTest extends \Codeception\Test\Unit
{
    public function testSimpleKeyValue()
    {
        $lexer = new Lexer('keyA:value1');
        $stream = $lexer->tokenize();
        $parser = new Parser($stream);
        $result = $parser->parse();

        $this->assertInstanceOf('WrongWare\SearchParser\Term', $result[0]);
        $this->assertEquals('keyA', $result[0]->getKey());
        $this->assertEquals('value1', $result[0]->getValue());
    }

    public function testSimpleKeyValueInParen()
    {
        $lexer = new Lexer('(keyA:value1)');
        $stream = $lexer->tokenize();
        $parser = new Parser($stream);
        $result = $parser->parse();

        $this->assertInstanceOf('WrongWare\SearchParser\Term', $result[0]);
        $this->assertEquals('keyA', $result[0]->getKey());
        $this->assertEquals('value1', $result[0]->getValue());
    }

    public function testKeyValueOrKeyValue()
    {
        $lexer = new Lexer('keyA:value1 or keyB:value2');
        $stream = $lexer->tokenize();
        $parser = new Parser($stream);
        $result = $parser->parse();

        $this->assertInstanceOf('WrongWare\SearchParser\Group', $result[0]);
        $this->assertEquals('or', $result[0]->getType());

        $terms = $result[0]->getTerms();
        $this->assertEquals('keyA', $terms[0]->getKey());
        $this->assertEquals('value1', $terms[0]->getValue());
        $this->assertEquals('keyB', $terms[1]->getKey());
        $this->assertEquals('value2', $terms[1]->getValue());
    }

    public function testKeyValueInParenOrKeyValue()
    {
        $lexer = new Lexer('(keyA:value1) or keyB:value2');
        $stream = $lexer->tokenize();
        $parser = new Parser($stream);
        $result = $parser->parse();

        $this->assertInstanceOf('WrongWare\SearchParser\Group', $result[0]);
        $this->assertEquals('or', $result[0]->getType());

        $terms = $result[0]->getTerms();
        $this->assertEquals('keyA', $terms[0]->getKey());
        $this->assertEquals('value1', $terms[0]->getValue());
        $this->assertEquals('keyB', $terms[1]->getKey());
        $this->assertEquals('value2', $terms[1]->getValue());
    }

    public function testKeyValueOrKeyValueInParen()
    {
        $lexer = new Lexer('keyA:value1 or (keyB:value2)');
        $stream = $lexer->tokenize();
        $parser = new Parser($stream);
        $result = $parser->parse();

        $this->assertInstanceOf('WrongWare\SearchParser\Group', $result[0]);
        $this->assertEquals('or', $result[0]->getType());

        $terms = $result[0]->getTerms();
        $this->assertEquals('keyA', $terms[0]->getKey());
        $this->assertEquals('value1', $terms[0]->getValue());
        $this->assertEquals('keyB', $terms[1]->getKey());
        $this->assertEquals('value2', $terms[1]->getValue());
    }

    public function testKeyValueInParenAndKeyValue()
    {
        $lexer = new Lexer('(keyA:value1) and keyB:value2');
        $stream = $lexer->tokenize();
        $parser = new Parser($stream);
        $result = $parser->parse();

        $this->assertInstanceOf('WrongWare\SearchParser\Group', $result[0]);
        $this->assertEquals('and', $result[0]->getType());

        $terms = $result[0]->getTerms();
        $this->assertEquals('keyA', $terms[0]->getKey());
        $this->assertEquals('value1', $terms[0]->getValue());
        $this->assertEquals('keyB', $terms[1]->getKey());
        $this->assertEquals('value2', $terms[1]->getValue());
    }

    public function testKeyValueAndKeyValueInParen()
    {
        $lexer = new Lexer('keyA:value1 and (keyB:value2)');
        $stream = $lexer->tokenize();
        $parser = new Parser($stream);
        $result = $parser->parse();

        $this->assertInstanceOf('WrongWare\SearchParser\Group', $result[0]);
        $this->assertEquals('and', $result[0]->getType());

        $terms = $result[0]->getTerms();
        $this->assertEquals('keyA', $terms[0]->getKey());
        $this->assertEquals('value1', $terms[0]->getValue());
        $this->assertEquals('keyB', $terms[1]->getKey());
        $this->assertEquals('value2', $terms[1]->getValue());
    }

    public function testInParenKeyValueAndKeyValue()
    {
        $lexer = new Lexer('(keyA:value1 and keyB:value2)');
        $stream = $lexer->tokenize();
        $parser = new Parser($stream);
        $result = $parser->parse();

        $this->assertInstanceOf('WrongWare\SearchParser\Group', $result[0]);
        $this->assertEquals('and', $result[0]->getType());

        $terms = $result[0]->getTerms();
        $this->assertEquals('keyA', $terms[0]->getKey());
        $this->assertEquals('value1', $terms[0]->getValue());
        $this->assertEquals('keyB', $terms[1]->getKey());
        $this->assertEquals('value2', $terms[1]->getValue());
    }

    public function testInParenKeyValueOrKeyValue()
    {
        $lexer = new Lexer('(keyA:value1 or keyB:value2)');
        $stream = $lexer->tokenize();
        $parser = new Parser($stream);
        $result = $parser->parse();

        $this->assertInstanceOf('WrongWare\SearchParser\Group', $result[0]);
        $this->assertEquals('or', $result[0]->getType());

        $terms = $result[0]->getTerms();
        $this->assertEquals('keyA', $terms[0]->getKey());
        $this->assertEquals('value1', $terms[0]->getValue());
        $this->assertEquals('keyB', $terms[1]->getKey());
        $this->assertEquals('value2', $terms[1]->getValue());
    }

    public function testInParenKeyValueOrKeyValueAndKeyValue()
    {
        $lexer = new Lexer('(keyA:value1 or keyB:value2) and keyC:value3');
        $stream = $lexer->tokenize();
        $parser = new Parser($stream);
        $result = $parser->parse();

        $this->assertInstanceOf('WrongWare\SearchParser\Group', $result[0]);
        $this->assertEquals('and', $result[0]->getType());

        $terms = $result[0]->getTerms();
        $this->assertInstanceOf('WrongWare\SearchParser\Group', $terms[0]);
        $this->assertEquals('or', $terms[0]->getType());
        $this->assertInstanceOf('WrongWare\SearchParser\Term', $terms[1]);

        $terms = $terms[0]->getTerms();
        $this->assertEquals('keyA', $terms[0]->getKey());
        $this->assertEquals('value1', $terms[0]->getValue());
        $this->assertEquals('keyB', $terms[1]->getKey());
        $this->assertEquals('value2', $terms[1]->getValue());
    }

    public function testInParenKeyValueOrKeyValueAndKeyValueInParen()
    {
        $lexer = new Lexer('(keyA:value1 or keyB:value2) and (keyC:value3)');
        $stream = $lexer->tokenize();
        $parser = new Parser($stream);
        $result = $parser->parse();

        $this->assertInstanceOf('WrongWare\SearchParser\Group', $result[0]);
        $this->assertEquals('and', $result[0]->getType());

        $terms = $result[0]->getTerms();
        $this->assertInstanceOf('WrongWare\SearchParser\Group', $terms[0]);
        $this->assertEquals('or', $terms[0]->getType());
        $this->assertInstanceOf('WrongWare\SearchParser\Term', $terms[1]);

        $terms = $terms[0]->getTerms();
        $this->assertEquals('keyA', $terms[0]->getKey());
        $this->assertEquals('value1', $terms[0]->getValue());
        $this->assertEquals('keyB', $terms[1]->getKey());
        $this->assertEquals('value2', $terms[1]->getValue());
    }

    public function testKeyValueOrKeyValueAndKeyValueInParen()
    {
        $lexer = new Lexer('keyA:value1 or keyB:value2 and (keyC:value3)');
        $stream = $lexer->tokenize();
        $parser = new Parser($stream);
        $result = $parser->parse();

        $this->assertInstanceOf('WrongWare\SearchParser\Group', $result[0]);
        $this->assertEquals('or', $result[0]->getType());

        $terms = $result[0]->getTerms();
        $this->assertInstanceOf('WrongWare\SearchParser\Term', $terms[0]);
        $this->assertInstanceOf('WrongWare\SearchParser\Group', $terms[1]);
        $this->assertEquals('and', $terms[1]->getType());

        $terms = $terms[1]->getTerms();
        $this->assertEquals('keyB', $terms[0]->getKey());
        $this->assertEquals('value2', $terms[0]->getValue());
        $this->assertEquals('keyC', $terms[1]->getKey());
        $this->assertEquals('value3', $terms[1]->getValue());
    }

    public function testKeyValueOrInParenKeyValueAndKeyValue()
    {
        $lexer = new Lexer('keyA:value1 or (keyB:value2 and keyC:value3)');
        $stream = $lexer->tokenize();
        $parser = new Parser($stream);
        $result = $parser->parse();

        $this->assertInstanceOf('WrongWare\SearchParser\Group', $result[0]);
        $this->assertEquals('or', $result[0]->getType());

        $terms = $result[0]->getTerms();
        $this->assertInstanceOf('WrongWare\SearchParser\Term', $terms[0]);
        $this->assertInstanceOf('WrongWare\SearchParser\Group', $terms[1]);
        $this->assertEquals('and', $terms[1]->getType());

        $terms = $terms[1]->getTerms();
        $this->assertEquals('keyB', $terms[0]->getKey());
        $this->assertEquals('value2', $terms[0]->getValue());
        $this->assertEquals('keyC', $terms[1]->getKey());
        $this->assertEquals('value3', $terms[1]->getValue());
    }

    public function testKeyValueOrKeyValueAndKeyValue()
    {
        $lexer = new Lexer('keyA:value1 or keyB:value2 and keyC:value3');
        $stream = $lexer->tokenize();
        $parser = new Parser($stream);
        $result = $parser->parse();

        $this->assertInstanceOf('WrongWare\SearchParser\Group', $result[0]);
        $this->assertEquals('or', $result[0]->getType());

        $terms = $result[0]->getTerms();
        $this->assertInstanceOf('WrongWare\SearchParser\Term', $terms[0]);
        $this->assertInstanceOf('WrongWare\SearchParser\Group', $terms[1]);
        $this->assertEquals('and', $terms[1]->getType());

        $terms = $terms[1]->getTerms();
        $this->assertEquals('keyB', $terms[0]->getKey());
        $this->assertEquals('value2', $terms[0]->getValue());
        $this->assertEquals('keyC', $terms[1]->getKey());
        $this->assertEquals('value3', $terms[1]->getValue());
    }

    public function testInParenKeyValueOrKeyValueAndKeyValue2()
    {
        $lexer = new Lexer('(keyA:value1 or keyB:value2 and keyC:value3)');
        $stream = $lexer->tokenize();
        $parser = new Parser($stream);
        $result = $parser->parse();

        $this->assertInstanceOf('WrongWare\SearchParser\Group', $result[0]);
        $this->assertEquals('or', $result[0]->getType());

        $terms = $result[0]->getTerms();
        $this->assertInstanceOf('WrongWare\SearchParser\Term', $terms[0]);
        $this->assertInstanceOf('WrongWare\SearchParser\Group', $terms[1]);
        $this->assertEquals('and', $terms[1]->getType());

        $terms = $terms[1]->getTerms();
        $this->assertEquals('keyB', $terms[0]->getKey());
        $this->assertEquals('value2', $terms[0]->getValue());
        $this->assertEquals('keyC', $terms[1]->getKey());
        $this->assertEquals('value3', $terms[1]->getValue());
    }

    public function testInParenKeyValueOrInParenKeyValueAndKeyValue()
    {
        $lexer = new Lexer('(keyA:value1 or (keyB:value2) and keyC:value3)');
        $stream = $lexer->tokenize();
        $parser = new Parser($stream);
        $result = $parser->parse();

        $this->assertInstanceOf('WrongWare\SearchParser\Group', $result[0]);
        $this->assertEquals('or', $result[0]->getType());

        $terms = $result[0]->getTerms();
        $this->assertInstanceOf('WrongWare\SearchParser\Term', $terms[0]);
        $this->assertInstanceOf('WrongWare\SearchParser\Group', $terms[1]);
        $this->assertEquals('and', $terms[1]->getType());

        $terms = $terms[1]->getTerms();
        $this->assertEquals('keyB', $terms[0]->getKey());
        $this->assertEquals('value2', $terms[0]->getValue());
        $this->assertEquals('keyC', $terms[1]->getKey());
        $this->assertEquals('value3', $terms[1]->getValue());
    }
}
