#!/bin/bash

cmd=`inotifywait -r -e CREATE -e MODIFY -e DELETE ./`

while inotifywait -r -e CREATE -e MODIFY -e DELETE ./
do
  echo
  php othello.php < tmp.txt
  echo
done
