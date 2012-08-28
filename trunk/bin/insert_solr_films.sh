#!/bin/sh

ABSPATH="$(cd "${0%/*}" 2>/dev/null; echo "$PWD"/"${0##*/}")"
SCRIPT_PATH=`dirname "$ABSPATH"`

source $SCRIPT_PATH/../public/environment.sh

getEnvironment

echo "Inserting Films"

php $SCRIPT_PATH/../public/symfony widget exec SolrManager frontend $ENVIRONMENT "add,films,'1|200'"
echo 'Done Inserting Films'

exit 0
