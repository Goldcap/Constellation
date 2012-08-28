#!/bin/sh

ABSPATH="$(cd "${0%/*}" 2>/dev/null; echo "$PWD"/"${0##*/}")"
SCRIPT_PATH=`dirname "$ABSPATH"`

source $SCRIPT_PATH/../public/environment.sh

getEnvironment

echo "Wiping Solr"

php $SCRIPT_PATH/../public/symfony widget exec SolrManager frontend $ENVIRONMENT wipe
echo 'Done Wiping Solr'

./insert_solr_users.sh
./insert_solr_films.sh
./insert_solr_screenings.sh
./insert_solr_payments.sh
./insert_solr_audience.sh 
