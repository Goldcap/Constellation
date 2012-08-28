#!/bin/bash
#Run First Day of Every Month at 4:00 AM EST

ABSPATH="$(cd "${0%/*}" 2>/dev/null; echo "$PWD"/"${0##*/}")"
SCRIPT_PATH=`dirname "$ABSPATH"`

source $SCRIPT_PATH/environment.sh

getEnvironment

#php $SCRIPT_PATH/symfony widget exec MessageManager frontend $ENVIRONMENT > /var/log/cronlog/cronlog.log

exit 0
