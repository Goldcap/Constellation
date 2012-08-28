#!/bin/bash

cd /var/www/html/sites/dev.tattoojohnny.com/public

if [ -z "$1" ];
then
  echo "Usage: wexec.sh widgetname args"
  exit 0
fi

ABSPATH="$(cd "${0%/*}" 2>/dev/null; echo "$PWD"/"${0##*/}")"
SCRIPT_PATH=`dirname "$ABSPATH"`

source $SCRIPT_PATH/environment.sh

getEnvironment

php symfony widget exec $1 frontend $ENVIRONMENT $2

exit 0
