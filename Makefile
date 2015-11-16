.PHONY:	dist clean test migrate generate install checkout coverage depend js package update-depend

SHELL=/bin/bash

all: checkout depend js migrate coverage package 
	echo "Up-to-date"

clean:
	bin/clean.sh

test:	clean
	bin/phpunit.phar --stderr test/php

migrate:
	bin/dbmigrate.sh $(VERSION)

#
# usage: make generate arg=<ClassName>
#
generate:
	bin/generate.sh $(arg)

checkout:
	git pull

coverage: clean
	bin/phpunit.phar --stderr --coverage-html doc/coverage test/php

depend:
	bin/depend.sh install

update-depend:
	bin/depend.sh update

js:
	bin/build.sh

package:
	bin/package.sh
