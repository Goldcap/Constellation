#!/bin/bash
#Run Every Morning at 2:00 AM EST

ABSPATH="$(cd "${0%/*}" 2>/dev/null; echo "$PWD"/"${0##*/}")"
SCRIPT_PATH=`dirname "$ABSPATH"`

source $SCRIPT_PATH/environment.sh

getEnvironment

#Update our orders, make sure we have the latest Verisign Data
#php $SCRIPT_PATH/symfony widget exec MigrationManager frontend $ENVIRONMENT pullrank >> /var/log/cronlog/cronlog.log
#php $SCRIPT_PATH/symfony widget exec MigrationManager frontend $ENVIRONMENT legacysales,$DATE,orders >> /var/log/cronlog/cronlog.log
#php $SCRIPT_PATH/symfony widget exec MigrationManager frontend $ENVIRONMENT legacysales,$DATE,orders_products >> /var/log/cronlog/cronlog.log

#Update our artist stats
php $SCRIPT_PATH/symfony widget exec ArchiveManager frontend $ENVIRONMENT create,artist_sales_stat,"YESTERDAY" >> /var/log/cronlog/cronlog.log
php $SCRIPT_PATH/symfony widget exec ArchiveManager frontend $ENVIRONMENT create,artist_product_stat,"YESTERDAY" >> /var/log/cronlog/cronlog.log

#Migrate Search Stats from Mongo to MySQL
#Make sure to use the Admin App
php $SCRIPT_PATH/symfony widget exec StatsAdmin admin $ENVIRONMENT search_stats_update >> /var/log/cronlog/cronlog.log

#Some Google Analytics Data
#php $SCRIPT_PATH/symfony widget exec MessageManager frontend $ENVIRONMENT DownloadExpiration >> /var/log/cronlog/cronlog.log
php $SCRIPT_PATH/symfony widget exec StatsAdmin frontend $ENVIRONMENT dailysales >> /var/log/cronlog/cronlog.log
php $SCRIPT_PATH/symfony widget exec GoogleService admin $ENVIRONMENT reindex >> /var/log/cronlog/cronlog.log

../bin/insert_solr_products.sh

exit 0
