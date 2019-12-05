#!/bin/bash

# xdebug is so slow, instead I often use pcov
# fake the xdebug as pcov using pcov-clobber
vendor/pcov/clobber/bin/pcov clobber;

# then let's run the coverage!
php -d pcov.enabled=1 ./phpunit --coverage-html coverage
