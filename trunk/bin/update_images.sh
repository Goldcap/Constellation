#!/bin/sh

ABSPATH="$(cd "${0%/*}" 2>/dev/null; echo "$PWD"/"${0##*/}")"
SCRIPT_PATH=`dirname "$ABSPATH"`

source $SCRIPT_PATH/../public/environment.sh

getEnvironment

FILENAME=$SCRIPT_PATH/../public/hosts.txt

echo "Updating Host Images"

while read line; do
    echo "$line"
	  #php $SCRIPT_PATH/../public/symfony widget exec ImageUpdater frontend $ENVIRONMENT "process,2"
done < "$FILENAME"

echo 'Updating Host Images'


exit 0
