root=/var/www/repo
dist=test
dest=$root/$dist

if [ ! -d $dest ]
then
  mkdir -p $dest || exit
fi

version=`head -1 debian/changelog | cut -d ' ' -f2 | tr -d '\(\)'`
project=`head -1 debian/changelog | cut -d ' ' -f1`

if [ -f $dest/${project}_${version}_all.deb ]
then
	echo "Debian pakken for version ${version} af ${project} findes allerede, har du husket at rette i debian changelog filen?"
	#exit 1
fi

debuild --no-lintian -us -uc -b

mv ../*.deb $dest
mv ../*.changes $dest
mv ../*.build $dest
cd $root
dpkg-scanpackages -m $dist /dev/null | gzip > $dist/Packages.gz

