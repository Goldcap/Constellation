#!/bin/sh

ABSPATH="$(cd "${0%/*}" 2>/dev/null; echo "$PWD"/"${0##*/}")"
SCRIPT_PATH=`dirname "$ABSPATH"`

source $SCRIPT_PATH/../public/environment.sh

getEnvironment

echo "Inserting Screenings into $ENVIRONMENT"

for i in $(seq 0 1000 25000)
do
	let j=$i+1000
	echo 'Inserting Screenings for "'$i'|'$j'"'
	php $SCRIPT_PATH/../public/symfony widget exec SolrManager frontend $ENVIRONMENT add,screenings,"$i|$j"
done

exit 0
