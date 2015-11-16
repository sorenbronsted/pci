#!/bin/sh
#set -x

CONFIG=test/pci.ini

if [ ! -f ${CONFIG} ]
then
  echo "${CONFIG} is not found"
  exit 1;
fi

USER=`grep user ${CONFIG} | head -1 | cut -d= -f2 | tr -d ' '`
PASSWORD=`grep password ${CONFIG} | head -1 | cut -d= -f2 | tr -d ' '`
HOST=`grep host ${CONFIG} | head -1| cut -d= -f2 | tr -d ' '`
DB=`grep name ${CONFIG} | head -1 | cut -d= -f2 | tr -d ' '`

if [ -n $HOST -a -n $USER -a -n $PASSWORD -a -n $DB ]
then
  mysql --host=$HOST --user=$USER --password=$PASSWORD $DB < database/sql/clean.sql
else
  echo "HOST, USER, PASSWORD and DB is not defined"
  exit 3;
fi

project=`head -1 debian/changelog | cut -d ' ' -f1`

if [ -d debian/${project}/ ]; then
    cd debian
	rm -rf ${project}
fi
