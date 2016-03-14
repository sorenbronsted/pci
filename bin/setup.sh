#!/bin/sh

set -e
#set -x

dbUser=root
dbPasswd=root
db=pci

dbExists=`echo "show databases" | mysql -u$dbUser -p$dbPasswd | grep ^$db | wc -l`
if [ $dbExists -eq 0 ]
then
		mysql -u$dbUser -p$dbPasswd < database/sql/create_db.sql
fi

if [ ! -f /etc/ufds/pci.ini ]
then
   cat conf/pci.ini | sudo sed -e 's/DBUSER/$dbUser/' -e 's/DBPASSWORD/$dbPasswd/' > /etc/ufds/pci.ini
fi
