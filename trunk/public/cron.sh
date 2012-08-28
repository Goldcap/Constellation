#!/bin/bash
#Run every Five Minutes

ABSPATH="$(cd "${0%/*}" 2>/dev/null; echo "$PWD"/"${0##*/}")"
SCRIPT_PATH=`dirname "$ABSPATH"`

source $SCRIPT_PATH/environment.sh

getEnvironment

echo "Running on "$(date) >> /var/log/cronlog/cronlog.log

#Send out Queued Messages
php $SCRIPT_PATH/symfony widget exec MessageManager frontend $ENVIRONMENT >> /var/log/cronlog/cronlog.log
php $SCRIPT_PATH/symfony widget exec UpcomingManager frontend $ENVIRONMENT >> /var/log/cronlog/cronlog.log
php $SCRIPT_PATH/symfony widget exec ImageUpdater frontend $ENVIRONMENT vow_check >> /var/log/cronlog/cronlog.log

#/usr/local/bin/python $SCRIPT_PATH/../bin/streaming.py --env=$ENVIRONMENT >> /var/log/cronlog/cronlog.log

#$SCRIPT_PATH/../bin/runscreens.sh >> /var/log/cronlog/cronlog.log

#Run for SOLR Replication
#if [ "$ENVIRONMENT" = "prod" ];
#then
#    /var/www/html/sites/solr.tattoojohnny.com/solr/bin/snappuller >> $SCRIPT_PATH/../logs/cronlog.txt
#    /var/www/html/sites/solr.tattoojohnny.com/solr/bin/snapinstaller >> $SCRIPT_PATH/../logs/cronlog.txt
#fi

exit 0
