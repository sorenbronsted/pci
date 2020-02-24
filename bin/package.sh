#!/bin/sh

if [ ! -d debian ]
then
	echo "No packing in this project"
	exit
fi

bin/composer.phar install --no-dev

dpkg-buildpackage -b -us -uc
name=`head -1 debian/changelog | cut -d ' ' -f1`
dest=/srv/pkg
if [ -d $dest ]
then
	mv ../${name}_* $dest
fi
