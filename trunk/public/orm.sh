#!/bin/bash


php symfony propel:build-schema --application=frontend --env=dev --connection=propel --xml
php symfony widget scaffold
rm -rf lib/model/base/*
php symfony widget crud

rm -rf config/*schema*

php symfony propel:build-schema --application=frontend --env=dev --connection=propel
php symfony propel:build-model

php symfony cc

exit 0
