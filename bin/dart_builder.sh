#!/usr/bin/env bash

if [ ! -f pubspec.yaml ]
then
  echo "Missing pubspec.yaml file"
  exit
fi 

cmd="dart2js -o web/main.dart.js web/main.dart"
# not working
#cmd="dartdevc -v -o web/main.dart.js web/main.dart"

function execute() {
    clear
    echo $cmd
    eval $cmd
    date
}

execute "$@"

inotifywait --quiet --recursive --monitor --event modify --format "%w%f" . \
| while read change
do
    #echo $change
    if [[ $change =~ dart___jb_tmp___$ ]]
    then
       execute
    fi
done
