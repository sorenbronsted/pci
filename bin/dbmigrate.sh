#!/bin/bash
#set -x
set -e

if [ ! -f ruckusing.conf.php ]
then
	echo "No database in this project"
	exit
fi

if [ $# -ge 2 ]
then
  echo "Wrong number of arguments"
  echo "Usage $0 [VERSION=<id>]"
  exit 1
fi

php -f vendor/bin/ruckus.php db:migrate $1

