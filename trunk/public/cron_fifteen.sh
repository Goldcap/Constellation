#!/bin/bash
#Run Every Hour at :10

ABSPATH="$(cd "${0%/*}" 2>/dev/null; echo "$PWD"/"${0##*/}")"
SCRIPT_PATH=`dirname "$ABSPATH"`

source $SCRIPT_PATH/environment.sh

getEnvironment
cd $SCRIPT_PATH
rsync -av data/movies sshacs@constell.upload.akamai.com:/113554
rsync -av web/uploads/screeningResources sshacs@constell.upload.akamai.com:/113554/uploads

exit 0
