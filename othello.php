<?php

class Othello {
    private $board;

    public function test() {
        echo "hello\n";
    }
}

function main() {
    $othello = new Othello();
    $othello->test();
}

main();