#!/bin/bash
#Run Every Hour at :10

ABSPATH="$(cd "${0%/*}" 2>/dev/null; echo "$PWD"/"${0##*/}")"
SCRIPT_PATH=`dirname "$ABSPATH"`

source $SCRIPT_PATH/environment.sh

getEnvironment

#Recreate the search Cache files
php $SCRIPT_PATH/symfony widget exec FetchIO frontend $ENVIRONMENT >> /var/log/cronlog/cronlog.log
php $SCRIPT_PATH/symfony widget exec CacheAdmin frontend $ENVIRONMENT searchcache >> /var/log/cronlog/cronlog.log
php $SCRIPT_PATH/symfony widget exec CacheAdmin frontend $ENVIRONMENT customsearchqueue >> /var/log/cronlog/cronlog.log

#cd /var/www/html/sites/www.tattoojohnny.com/sql/backup/ttj && mysqldump -uamadsen -p1hsvy5qb --skip-extended-insert ttj > ttj.sql && git commit -am "Updating TTJ Backup" > /var/www/html/sites/www.tattoojohnny.com/logs/backup_dblog.txt

exit 0
