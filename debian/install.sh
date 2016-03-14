#!/bin/sh
set -e
#set -x

if [ $# -ne 1 ]
then
  echo "Wrong number of arguments"
  echo "Usage $0 <destdir>"
  exit 1
fi

appdir="var/www/pci"
dest=$1

#
# Copy dirs
dirs="application database public scripts vendor"
for dir in $dirs
do
  cp -r $dir $dest/$appdir
done

# Copy files
files="readme.txt ruckusing.conf.php"
for file in $files
do
  cp $file $dest/$appdir
done

#
# In ubuntu 13.04 this information is put into kanban.conf
# but in 12.04 it is the old way
cp conf/htaccess $dest/$appdir/public/.htaccess

#
# Application config
cp conf/pci.ini $dest/etc/ufds/

#
# Apache config
cp conf/pci.conf $dest/etc/apache2/sites-available/

