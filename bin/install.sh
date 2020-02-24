#!/bin/sh -e
set -e
#set -x

if [ $# -ne 1 ]
then
  echo "Wrong number of arguments"
  echo "Usage $0 <destdir>"
  exit 1
fi

name=`head -1 debian/changelog | cut -d ' ' -f1`
dest=$1
appdir=$dest/var/www/${name}

if [ ! -d $appdir ]
then
	mkdir -p $appdir
fi
#
rsync_opt="--exclude '.gi*'"
if [ -f debian/excludes.txt ]
then
	rsync_opt="--exclude-from=debian/excludes.txt"
fi

#
# Copy files
dirs="application database readme.* ruckusing.conf.php vendor migrations utils"
for dir in $dirs
do
	if [ -d $dir ] || [ -f $dir ]
	then
		rsync -ra $rsync_opt $dir $appdir
	fi
done

# Old dart build
if [ -d public/build ]
then
	rsync -ra public/build/web $appdir/public
fi

# New js build
web="public/web"
if [ -d $web ]
then
	for dir in css html js
	do
		if [ -d $web/$dir ]
		then
			mkdir -p $appdir/$web
			rsync -ra $rsync_opt $web/$dir $appdir/$web
		fi
	done
fi

# Any other php files
if [ -d public ]
then
	rsync -a public/*.php $appdir/public
fi

#
# Application config
if [ -f conf/${name}.ini ]
then
  install -D -m 644 conf/${name}.ini $dest/etc/ufds/${name}.ini
fi

#
# Apache config
if [ -f conf/htaccess ]
then
	install -D -m 644 conf/htaccess $appdir/public/.htaccess
fi

if [ -f conf/${name}.conf ]
then
  install -D -m 644 conf/${name}.conf $dest/etc/apache2/sites-available/${name}.conf
fi

#
# cron
if [ -f conf/${name}.cron ]
then
	install -D -m 644 conf/${name}.cron $dest/etc/cron.d/${name}
fi

