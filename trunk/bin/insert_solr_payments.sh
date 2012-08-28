#!/bin/sh

ABSPATH="$(cd "${0%/*}" 2>/dev/null; echo "$PWD"/"${0##*/}")"
SCRIPT_PATH=`dirname "$ABSPATH"`

source $SCRIPT_PATH/../public/environment.sh

getEnvironment

echo "Inserting Payments"

php $SCRIPT_PATH/../public/symfony widget exec SolrManager frontend $ENVIRONMENT "add,payments,'1|3000'"
php $SCRIPT_PATH/../public/symfony widget exec SolrManager frontend $ENVIRONMENT "add,payments,'3001|6000'"
php $SCRIPT_PATH/../public/symfony widget exec SolrManager frontend $ENVIRONMENT "add,payments,'6001|9000'"

echo 'Done Inserting Payments'

exit 0
