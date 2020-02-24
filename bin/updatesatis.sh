#!/bin/sh
set -e
#set -x
bin/satis.phar build --quiet ../build/satis.json /var/www/html/satis
