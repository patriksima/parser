<?php

namespace WrongWare\EBNFParser;

use WrongWare\EBNFParser\Lexer;
use WrongWare\EBNFParser\Parser;

require __DIR__ . '/vendor/autoload.php';

class App
{
    public function run()
    {
        $lexer = new Lexer('(key:value or key:value) and key:value)');
        $stream = $lexer->tokenize();
        print_r($stream);
        $parser = new Parser($stream);
        $result = $parser->parse();
        print_r($result);
    }
}

(new App)->run();
