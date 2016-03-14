#!/bin/sh

set -e

if [ $# -ne 2 ]
then
   echo "Wrong number off arguments."
   echo "Usage: $0 <dir> <git-url>"
   exit 1;
fi

dir=$1
url=$2

if [ ! -d $dir ]
then
   mkdir -p $dir
fi

cd $dir

if [ -d .git ]
then
   git pull
else
   cd ..
   git clone $url
fi

