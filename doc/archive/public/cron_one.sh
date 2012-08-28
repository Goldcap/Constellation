#!/bin/bash
#Run every Five Minutes

ABSPATH="$(cd "${0%/*}" 2>/dev/null; echo "$PWD"/"${0##*/}")"
SCRIPT_PATH=`dirname "$ABSPATH"`

source $SCRIPT_PATH/environment.sh

getEnvironment

#Move wishlist data from the temp stack to the user account
php $SCRIPT_PATH/symfony widget exec ArchiveManager frontend $ENVIRONMENT create,wishlist_migration >> /var/log/cronlog/cronlog.log

#Cache items that were "queued" by a user search
#php $SCRIPT_PATH/symfony widget exec CacheAdmin frontend $ENVIRONMENT searchqueue >> /var/log/cronlog/cronlog.log


exit 0
