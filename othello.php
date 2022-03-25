<?php

class Othello {
    private const WIDTH = 8;
    private const HEIGHT = 8;
    private const EMPTY = 0;
    private const BLACK = 1;
    private const WHITE = 2;
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

    // 盤面を出力
    function print_board() {
        // column
        echo "  ";
        for ($i = 0; $i < self::WIDTH; $i++)
            echo $i;
        echo "\n";

        $i = 0;
        foreach ($this->board as $row) {
            echo $i++." "; 
            foreach ($row as $cell) {
                echo $this->piece[$cell];
            }
            echo "\n";
        }
    }

    // ターンを出力
    function print_turn() {
        echo "It's '" . $this->piece[$this->turn] . "' turn.\n";
    }

    // 相手のコマ
    function enemy_piece() {
        return self::BLACK + self::WHITE - $this->turn;
    }

    // 自分のコマ
    function my_piece() {
        return $this->turn;
    }

    // 盤面内か
    function is_within_board($y, $x) {
        return (0 <= $y && $y < self::HEIGHT)
                || (0 <= $x && $x < self::WIDTH);
    }

    // その方向はひっくり返せるか
    function can_reverse_in_one_direction($y, $x, $dy, $dx) {
        // 隣は相手のコマ
        $y += $dy;
        $x += $dx;
        if (!$this->is_within_board($y, $x)
            || $this->board[$y][$x] != $this->enemy_piece())
        {
            return false;
        }

        // 相手のコマの先に自分の駒がある
        while ($this->is_within_board($y + $dy, $x + $dx)
            && $this->board[$y][$x] == $this->enemy_piece())
        {
            $y += $dy;
            $x += $dx;
        }
        if ($this->board[$y][$x] != $this->my_piece())
            return false;

        return true;
    }

    // 指定座標が置けるか
    function can_put_piece(int $y, int $x) {
        if ($this->board[$y][$x] != self::EMPTY)
            return false;
        
        for ($dy = -1; $dy <= 1; $dy++) {
            for ($dx = -1; $dx <= 1; $dx++) {
                if ($dy == 0 && $dx == 0)
                    continue;
                if ($this->can_reverse_in_one_direction($y, $x, $dy, $dx))
                    return true;
            }
        }
        return false;
    }

    // ユーザー入力
    function input_piece() {
        function usage() { echo "expected: '[0~7] [0~7]'\n"; }
        function invalid_input() { echo "invalid input\n"; }

        while (true) {
            // ユーザー入力
            echo "please input => ";
            $input = str_replace(PHP_EOL, '', fgets(STDIN));
            
            // 入力の形式チェック
            if (strlen($input) != 3
                || !preg_match('/^[0-7] [0-7]$/', $input))
            {
                usage();
                continue;
            }

            // 盤面を加味したチェック
            $y = (int)$input[0];
            $x = (int)$input[2];
            if (!$this->can_put_piece($y, $x)) {
                invalid_input();
                continue;
            }
            
            return [$y, $x];
        }
    }
}

function main() {
    $game = new Othello();
    while (true) {
        $game->print_board();
        $game->print_turn();

        [ $y, $x ] = $game->input_piece();
        echo $y."\n";
        echo $x."\n";
        break;
    }
}

main();
