#!/bin/sh

ABSPATH="$(cd "${0%/*}" 2>/dev/null; echo "$PWD"/"${0##*/}")"
SCRIPT_PATH=`dirname "$ABSPATH"`

source $SCRIPT_PATH/../public/environment.sh

getEnvironment

echo "Inserting Audiences into $ENVIRONMENT"

php $SCRIPT_PATH/../public/symfony widget exec SolrManager frontend $ENVIRONMENT "add,audiences,'1|2000'"
php $SCRIPT_PATH/../public/symfony widget exec SolrManager frontend $ENVIRONMENT "add,audiences,'2001|4000'"
php $SCRIPT_PATH/../public/symfony widget exec SolrManager frontend $ENVIRONMENT "add,audiences,'4001|6000'"
php $SCRIPT_PATH/../public/symfony widget exec SolrManager frontend $ENVIRONMENT "add,audiences,'6001|9000'"
echo 'Done Inserting Audiences'


exit 0
