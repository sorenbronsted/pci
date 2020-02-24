#!/bin/sh
set -e
#set -x

testdir=test
if [ -x $testdir/pretest.sh ]
then
  $testdir/pretest.sh
fi

majorVersion=`php -r 'echo PHP_MAJOR_VERSION;'`
minorVersion=`php -r 'echo PHP_MINOR_VERSION;'`

if [ -f vendor/bin/phpunit ]
then
   vendor/bin/phpunit --configuration test-conf.xml $*
elif [ $majorVersion -eq 7 ]
then
    if [ $minorVersion -gt 1 ]
    then
        bin/phpunit_8.phar --configuration test-conf.xml $*
    else
        bin/phpunit_7.phar --configuration test-conf.xml $*
    fi
else
  bin/phpunit.phar --configuration test-conf.xml $*
fi


