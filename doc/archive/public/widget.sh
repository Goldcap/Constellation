#!/bin/bash

cd /var/www/html/sites/dev.tattoojohnny.com/public

if [ -z "$1" ];
then
  echo "Usage: widget.sh widgetname"
  exit 0
fi

php symfony widget widget $1
chown apache:webusers lib/widgets/$1 -R
chmod 775 lib/widgets/$1 -R

php symfony cc

exit 0
