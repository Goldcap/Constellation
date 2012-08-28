#!/bin/bash

chmod 775 * -R
chmod 775 ../bin/* -R
chown apache:webusers * -R
chown apache:webusers ../bin/* -R
chmod 777 cache log data web/uploads -R

exit 0
