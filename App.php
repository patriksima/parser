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
        $lexer = new Lexer('(key:value or key:value) and key:value');
        $stream = $lexer->tokenize();
        $parser = new Parser($stream);
        $result = $parser->parse();
        print_r($result);
    }
}

(new App())->run();
