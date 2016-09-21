<?php

namespace WrongWare\SearchParser;

require __DIR__.'/vendor/autoload.php';

/**
 * Example app.
 */
class App
{
    /**
     * Run example app.
     */
    public function run()
    {
        $lexer = new Lexer('keyA:value1 or keyB:value2 and (keyC:value3 or keyD:value4)');
        $stream = $lexer->tokenize();
        $parser = new Parser($stream);
        $result = $parser->parse();
        print_r($result);
    }
}

(new App())->run();
