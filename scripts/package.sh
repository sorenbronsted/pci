#!/bin/sh

# exit on any error
set -e

# Check arguments
if [ $# -ne 1 ]
then
   echo "Wrong number off arguments."
   echo "Usage: $0 <dir>"
   exit 1
fi

# Check that dir exists
dir=$1
if [ ! -d $dir ]
then
	echo "dir $dir not found"
	exit 1
fi
cd $dir

# Check that it we can build a package
if [ ! -f debian/control ]
then
	echo "debian/control not found"
	exit 1
fi
project=$(cat debian/control | grep Source | cut -d ' ' -f2)

# Check that destination exists
pkgDir=/srv/apt/ufds/
if [ ! -d $pkgDir ]
then
	mkdir -p $pkgDir
fi

poolDir=${pkgDir}/ufds/pool/test
if [ ! -d $poolDir ]
then
	mkdir -p $poolDir
fi

distDir=${pkgDir}/ufds/dists/precise/test
if [ ! -d $distDir ]
then
	mkdir -p $distDir
fi

# Make source package
debuild --no-lintian -us -uc -S

# Get the version number
version=$(ls ../${project}*.dsc | cut -d '_' -f2 | sed -e 's/\.dsc//')

# Move to destination dir
mv ../${project}_* $poolDir

# Build the package from pool dir
cd $poolDir
pbuilder build ${project}_${version}.dsc

# Move package to pool dir
mv /var/cache/pbuilder/result/${project}_* .

# Make package index
dpkg-scanpackages -m . /dev/null | gzip > $distDir/Package.gz