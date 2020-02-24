.PHONY:	clean test migrate generate coverage depend js package update-depend

SHELL=/bin/bash

all: depend coverage satis package
	@echo "Up-to-date"

clean:
	bin/clean.sh

test: migrate clean
	bin/test.sh

testui:
	bin/testui.sh

migrate: 
	bin/dbmigrate.sh $(VERSION)

# usage: make generate arg=<ClassName>
generate:
	bin/generate.sh $(arg)

coverage: migrate clean
	bin/test.sh --coverage-html doc/coverage

depend:
	bin/depend.sh install

update-depend:
	bin/depend.sh update --no-progress --no-suggest

js:
	bin/build.sh

package: js
	bin/package.sh

install:
	bin/install.sh $(DESTDIR)

satis:
	bin/updatesatis.sh
