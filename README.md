# php-othello
コマンドラインで動くオセロを作成しました。

## 環境
```sh
$ php --version
PHP 8.1.4 (cli) (built: Mar 18 2022 18:10:55) (NTS)
Copyright (c) The PHP Group
Zend Engine v4.1.4, Copyright (c) Zend Technologies

$ docker --version
Docker version 20.10.12, build e91ed57

$ docker-compose --version
docker-compose version 1.29.2, build 5becea4c
```

## usage
```sh
# 起動
php othello.php
# or 起動(docker)
docker-compose run php php othello.php

# 表示
>   01234567
> 0 ........
> 1 ........
> 2 ........
> 3 ...xo...
> 4 ...ox...
> 5 ........
> 6 ........
> 7 ........
> It`s 'o' turn.
> please input =>

# 入力
3 2
>   01234567
> 0 ........
> 1 ........
> 2 ........
> 3 ..ooo...
> 4 ...ox...
> 5 ........
> 6 ........
> 7 ........
> It`s 'x' turn.
> please input =>

# 終了
exit
> Bye.

```
