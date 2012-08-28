#!/bin/bash
#Run Every Morning at 2:00 AM EST

ABSPATH="$(cd "${0%/*}" 2>/dev/null; echo "$PWD"/"${0##*/}")"
SCRIPT_PATH=`dirname "$ABSPATH"`

source $SCRIPT_PATH/environment.sh

getEnvironment

#Some Google Analytics Data
#php $SCRIPT_PATH/symfony widget exec GoogleAnalytics frontend $ENVIRONMENT >> /var/log/cronlog/cronlog.log

exit 0
