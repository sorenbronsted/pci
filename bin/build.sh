#!/bin/sh
set -e
#set -x

if [ -f public/pubspec.yaml ]
then
	export PATH=$PATH:/usr/lib/dart/bin
	cd public
	pub get
	pub build
fi

if [ -f package.json ]
then
	npm ci
	npm run bundle
fi
