#!/bin/sh

ABSPATH="$(cd "${0%/*}" 2>/dev/null; echo "$PWD"/"${0##*/}")"
SCRIPT_PATH=`dirname "$ABSPATH"`

source $SCRIPT_PATH/../public/environment.sh

getEnvironment

echo "Inserting Users"

php $SCRIPT_PATH/../public/symfony widget exec SolrManager frontend $ENVIRONMENT "add,users,'1|5000'"
php $SCRIPT_PATH/../public/symfony widget exec SolrManager frontend $ENVIRONMENT "add,users,'5001|9000'"
echo 'Done Inserting Users'


exit 0
