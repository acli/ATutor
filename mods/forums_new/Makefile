all: test

test: testing_framework
	./run-tests.sh

testing_framework: simpletest/TIMESTAMP run-tests.sh

simpletest/TIMESTAMP: simpletest.tar.gz
	tar xzf $<
	date > $@

simpletest.tar.gz:
	wget -O $@ "`wget -q -O /dev/stdout http://www.simpletest.org/en/download.html | sed -e '1,/<a name="current-release">/d'|grep '<a href'|head -1|sed -e 's/^.*href="//' -e 's/">.*//'`"

.DELETE_ON_ERROR:
.PHONEY: all check test testing_framework
