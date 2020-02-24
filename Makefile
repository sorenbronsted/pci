.PHONY:	create_db test migrate generate coverage depend js package update-depend

SHELL=/bin/bash

all: depend coverage satis package
	@echo "Up-to-date"

create_db:
	mysql -uroot -proot < database/sql/drop_db.sql
	mysql -uroot -proot < database/sql/create_db.sql

test: create_db migrate
	vendor/bin/phpunit --configuration test-conf.xml

testui:
	vendor/bin/phpunit --configuration testui-conf.xml

migrate: 
	vendor/bin/ruckus.php db:migrate

# usage: make generate arg=<ClassName>
generate:
	vendor/bin/ruckus.php db:generate $(arg)

coverage: create_db migrate
	vendor/bin/phpunit --configuration test-conf.xml --coverage-html doc/coverage

depend:
	bin/composer.phar install --no-progress --no-suggest

update-depend:
	bin/composer.phar update --no-progress --no-suggest

js:
	npm ci
	npm run bundle

package: js
	bin/package.sh

install:
	bin/install.sh $(DESTDIR)

satis:
	bin/satis.phar build --quiet bin/satis.json /var/www/html/satis
