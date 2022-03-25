#!/bin/bash

while inotifywait -r -e CREATE -e MODIFY -e DELETE ./
do
  echo
  php othello.php < tmp.txt
  echo
done
