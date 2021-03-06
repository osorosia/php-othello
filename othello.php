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
        echo "\n  ";
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
                && (0 <= $x && $x < self::WIDTH);
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

    // その方向をひっくり返す
    function reverse_in_one_direction($y, $x, $dy, $dx) {
        $this->board[$y][$x] = $this->my_piece();

        while ($this->is_within_board($y + $dy, $x + $dx)
                && $this->board[$y + $dy][$x + $dx] == $this->enemy_piece())
        {
            $y += $dy;
            $x += $dx;
            $this->board[$y][$x] = $this->my_piece();
        }
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

    // 指定座標にコマを置く
    function put_piece(int $y, int $x) {
        for ($dy = -1; $dy <= 1; $dy++) {
            for ($dx = -1; $dx <= 1; $dx++) {
                if ($dy == 0 && $dx == 0)
                    continue;
                if ($this->can_reverse_in_one_direction($y, $x, $dy, $dx))
                    $this->reverse_in_one_direction($y, $x, $dy, $dx);
            }
        }
    }

    // ユーザー入力
    function input_piece() {
        $usage = function() { echo "expected: '[0~7] [0~7]'\n"; };
        $invalid_input = function() { echo "invalid input\n"; };

        while (true) {
            // ユーザー入力
            echo "please input => ";
            $input = str_replace(PHP_EOL, '', fgets(STDIN));

            if (strcmp($input, 'exit') == 0) {
                echo "\nBye.\n";
                exit(0);
            }

            // 入力の形式チェック
            if (strlen($input) != 3
                || !preg_match('/^[0-7] [0-7]$/', $input))
            {
                $usage();
                continue;
            }

            // 盤面を加味したチェック
            $y = (int)$input[0];
            $x = (int)$input[2];
            if (!$this->can_put_piece($y, $x)) {
                $invalid_input();
                continue;
            }
            
            echo "(y, x) = ("
                . $y
                . ", "
                . $x
                . ")\n";
            return [$y, $x];
        }
    }

    // ターン交代
    function change_turn() {
        $this->turn = self::BLACK + self::WHITE - $this->turn;
    }

    // 置ける場所があるか
    function can_put_piece_anywhere() {
        for ($y = 0; $y < self::HEIGHT; $y++) {
            for ($x = 0; $x < self::WIDTH; $x++) {
                if ($this->can_put_piece($y, $x))
                    return true;
            }
        }
        return false;
    }

    function finish_game() {
        $black = 0;
        $white = 0;
        foreach ($this->board as $arr) {
            $output = array_count_values($arr);
            $black += $output[self::BLACK];
            $white += $output[self::WHITE];
        }
        if ($black > $white)
            echo "'".$this->piece[self::BLACK]."' win.\n";
        else if ($black < $white)
            echo "'".$this->piece[self::WHITE]."' win.\n";
        else
            echo "draw.\n";
    }
}

function main() {
    $game = new Othello();

    while (true) {
        // 盤面出力
        $game->print_board();

        // 両プレイヤーとも置けないなら終了
        if (!$game->can_put_piece_anywhere()) {
            $game->change_turn();
            if (!$game->can_put_piece_anywhere()) {
                $game->finish_game();
                exit(0);
            }
        }

        // ターン表示
        $game->print_turn();

        // ユーザー入力
        [ $y, $x ] = $game->input_piece();

        // コマを置く
        $game->put_piece($y, $x);

        // ターン交代
        $game->change_turn();
    }
}

main();
