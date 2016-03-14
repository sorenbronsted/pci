#/bin/sh
set -e

export PATH=$PATH:/usr/lib/dart/bin

cd public/client
pub get
dart2js -omain.dart.js main.dart

