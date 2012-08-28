#!/bin/bash

ssh www.operislabs.com rm -rf /var/www/html/sites/backupsolr.constellation.tv/src/data/index/*
rsync -Paz /var/www/html/sites/solr.constellation.tv/* root@www.operislabs.com:/var/www/html/sites/backupsolr.constellation.tv

exit 0
