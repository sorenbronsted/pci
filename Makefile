.PHONY:	clean test migrate generate coverage depend js update-depend

SHELL=/bin/bash

all: depend js migrate coverage
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

coverage: clean
	bin/phpunit.phar --stderr --coverage-html doc/coverage test/php

depend:
	bin/depend.sh install

update-depend:
	bin/depend.sh update

js:
	bin/build.sh
