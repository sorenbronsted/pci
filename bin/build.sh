#/bin/sh
set -e

cd public/client
pub get
dart2js -omain.dart.js main.dart

