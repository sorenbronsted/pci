#!/bin/sh
#set -x

project=$(basename $(pwd))

if [ -d debian/${project}/ ]
then
	rm -rf debian/${project}/
fi

CONFIG=test/${project}.ini
if [ ! -f ${CONFIG} ]
then
  echo "No database cleaning"
  exit
fi

DRIVER=`bin/iniVar.sh $CONFIG defaultDb driver`
if [ -z "$DRIVER" ]
then
  exit
fi 

if [ $DRIVER != "mysql" ]
then
  echo "Not a mysql database"
  exit
fi
HOST=`bin/iniVar.sh $CONFIG defaultDb host`
DB=`bin/iniVar.sh $CONFIG defaultDb name`
USER=`bin/iniVar.sh $CONFIG defaultDb user`
PASSWORD=`bin/iniVar.sh $CONFIG defaultDb password`

if [ -n $HOST -a -n $DB ]
then
  mysql --host=$HOST --user=$USER --password=$PASSWORD $DB < database/sql/clean.sql
else
  echo "HOST and DB is not defined"
  exit 1;
fi
