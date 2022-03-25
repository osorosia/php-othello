<?php

class Othello {
    const WIDTH = 8;
    const HEIGHT = 8;
    const EMPTY = 0;
    const BLACK = 1;
    const WHITE = 2;
    private $piece;
    private $board;
    private $turn;

    // コンストラクタ
    function __construct() {
        $this->board = array(
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 2, 1, 0, 0, 0),
            array(0, 0, 0, 1, 2, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
        );

        $this->piece = array(
            self::EMPTY => '.',
            self::BLACK => 'o', 
            self::WHITE => 'x',
        );

        $this->turn = self::BLACK;
    }

    // メイン関数
    public function start() {
        while (true) {

            $this->print_board();
            $this->print_turn();
            break;
        }
    }

    // 盤面を出力
    private function print_board() {
        foreach ($this->board as $row) {
            foreach ($row as $cell) {
                echo $this->piece[$cell];
            }
            echo "\n";
        }
    }

    // ターンを出力
    private function print_turn() {
        echo "It's '" . $this->piece[$this->turn] . "' turn.\n";
    }
}

function main() {
    $game = new Othello();
    $game->start();
}

main();