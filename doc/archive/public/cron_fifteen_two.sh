#!/bin/bash
#Run Every Hour at :10

ABSPATH="$(cd "${0%/*}" 2>/dev/null; echo "$PWD"/"${0##*/}")"
SCRIPT_PATH=`dirname "$ABSPATH"`

source $SCRIPT_PATH/environment.sh

getEnvironment

#php $SCRIPT_PATH/symfony widget exec ArchiveManager frontend $ENVIRONMENT create,beacon_stat >> /var/log/cronlog/cronlog.log
#php $SCRIPT_PATH/symfony widget exec ArchiveManager frontend $ENVIRONMENT create,search_stat >> /var/log/cronlog/cronlog.log

#php $SCRIPT_PATH/symfony widget exec ArchiveManager frontend $ENVIRONMENT create,legacy_stat >> /var/log/cronlog/cronlog.log

exit 0
