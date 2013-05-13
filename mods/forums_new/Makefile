COMPOSER_BINDIR=vendor/bin

TESTING_FRAMEWORK_BINARIES=$(COMPOSER_BINDIR)/behat $(COMPOSER_BINDIR)/phpspec

all: test

test: testing_framework

testing_framework: $(TESTING_FRAMEWORK_BINARIES) composer.phar

composer.phar:
	curl http://getcomposer.org/installer | php

#$(TESTING_FRAMEWORK_BINARIES): composer.lock
#
#composer.lock: composer.json composer.phar
#	php composer.phar install --prefer-source --dev

$(COMPOSER_BINDIR)/%: composer.json composer.phar
	php composer.phar install --prefer-source --dev

.DELETE_ON_ERROR:
.PHONEY: all check test testing_framework
