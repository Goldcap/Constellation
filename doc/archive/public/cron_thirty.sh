#!/bin/bash
#Run Every Hour at :10

ABSPATH="$(cd "${0%/*}" 2>/dev/null; echo "$PWD"/"${0##*/}")"
SCRIPT_PATH=`dirname "$ABSPATH"`

source $SCRIPT_PATH/environment.sh

getEnvironment

php $SCRIPT_PATH/symfony widget exec artist_users frontend $ENVIRONMENT selectlist > $SCRIPT_PATH/cgi-bin/artist_select.txt
#php $SCRIPT_PATH/symfony widget exec MigrationManager frontend $ENVIRONMENT pullwishlists >> /var/log/cronlog/cronlog.log

exit 0
