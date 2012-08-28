#/usr/bin/bash

export TZ="America/New_York"

if [ -z "$1" ];
then
  LIBROOT="/var/www/html/lib"
else
  LIBROOT=$1
fi

if [ -z "$2" ];
then
  SITEROOT="/var/www/html/sites/dev.goodcircle.onitdigital.com"
else
  SITEROOT=$2
fi

if [ -z "$3" ];
then
  PROJECT="goodcircle"
else
  PROJECT=$3
fi

if [ -z "$4" ];
then
  DEBUG="true"
else
  DEBUG="false"
fi

echo "DEBUG="$DEBUG

#Remove all old files
rm -rf $SITEROOT/logs/creole_gen.log
rm -rf $SITEROOT/logs/propel_gen.log
rm -rf $SITEROOT/logs/wtvr_gen.log
rm -rf $SITEROOT/lib/schema/schema.xml

cd $SITEROOT/lib/schema/

if test $DEBUG = "false"
then
`$LIBROOT/phing/bin/phing -Dproject=creole > $SITEROOT/logs/creole_gen.log 2>&1`
mv $SITEROOT/lib/schema/schema.xml $SITEROOT/lib/schema/schema_last.xml
sed -e 's/type=\"\"/type=\"VARBINARY\"/g' $SITEROOT/lib/schema/schema_last.xml >> $SITEROOT/lib/schema/schema.xml
`$LIBROOT/propel/bin/propel-gen > $SITEROOT/logs/propel_gen.log 2>&1`
else
echo "Running $LIBROOT/phing/bin/phing -Dproject=$PROJECT creole"
`$LIBROOT/phing/bin/phing -Dproject=$PROJECT creole > $SITEROOT/logs/creole_gen.log 2>&1`
echo "Replacing missing types in $SITEROOT/lib/schema/schema.xml"
mv $SITEROOT/lib/schema/schema.xml $SITEROOT/lib/schema/schema_last.xml
sed -e 's/type=\"\"/type=\"VARBINARY\"/g' $SITEROOT/lib/schema/schema_last.xml >> $SITEROOT/lib/schema/schema.xml
echo "Running $LIBROOT/propel/bin/propel-gen"
`$LIBROOT/propel/bin/propel-gen > $SITEROOT/logs/propel_gen.log 2>&1`
fi

if test $DEBUG = "false"
then
  
  rm -rf ../$PROJECT > $SITEROOT/logs/wtvr_gen.log 2>&1
  if [ ! -d ../$PROJECT/om ]; then
    echo "Making om subdir"
    mkdir ../$PROJECT/om
  fi
  if [ ! -d ../$PROJECT/map ]; then
    echo "Making map subdir"
    mkdir ../$PROJECT/map
  fi
  if [ ! -d build ]; then
    echo "Making build subdir"
    mkdir build
  fi
  if [ ! -d build/classes ]; then
    echo "Making build classes subdir"
    mkdir build/classes
  fi
  if [ ! -d build/classes/$PROJECT ]; then
    echo "Making build project subdir"
    mkdir build/classes/$PROJECT
  fi
  if [ ! -d build/classes/$PROJECT/om ]; then
    echo "Making build project om subdir"
    mkdir build/classes/$PROJECT/om
  fi
  if [ ! -d build/classes/$PROJECT/map ]; then
    echo "Making build project map subdir"
    mkdir build/classes/$PROJECT/map
  fi
  if [ ! -d build/conf ]; then
    echo "Making build conf subdir"
    mkdir build/conf
  fi
  if [ ! -d build/sql ]; then
    echo "Making build sql subdir"
    mkdir build/sql
  fi
  cp build/classes/$PROJECT/*.php ../$PROJECT/ -R > $SITEROOT/logs/wtvr_gen.log 2>&1
  cp build/classes/$PROJECT/om/*.php ../$PROJECT/om/ -R > $SITEROOT/logs/wtvr_gen.log 2>&1
  cp build/classes/$PROJECT/map/*.php ../$PROJECT/map/ -R > $SITEROOT/logs/wtvr_gen.log 2>&1

  chown apache:apache ../$PROJECT -R
  rm -rf ../conf/$PROJECT-conf.php
  cp build/conf/$PROJECT-conf.php ../conf/
  
else
  
  echo "Working in: " `pwd`
  
  if [ ! -d ../$PROJECT ]; then
    echo "Creating Base Directory "
    mkdir ../$PROJECT
  fi
  
  echo "Removing Old Project Files"
  rm -rf ../$PROJECT/om/*.php
  rm -rf ../$PROJECT/map/*.php
  rm -rf ../$PROJECT/*.php
  
  echo "Copying New Project Files to $SITEROOT/lib/"
  echo "Working in `pwd`"
  echo "Testing for directories.. "
  
  echo "../$PROJECT/om"
  if [ ! -d ../$PROJECT/om ]; then
    echo "Making om subdir"
    mkdir ../$PROJECT/om
  fi
  echo "../$PROJECT/map"
  if [ ! -d ../$PROJECT/map ]; then
    echo "Making map subdir"
    mkdir ../$PROJECT/map
  fi
  if [ ! -d build ]; then
    echo "Making build subdir"
    mkdir build
  fi
  if [ ! -d build/classes ]; then
    echo "Making build classes subdir"
    mkdir build/classes
  fi
  if [ ! -d build/classes/$PROJECT ]; then
    echo "Making build project subdir"
    mkdir build/classes/$PROJECT
  fi
  if [ ! -d build/classes/$PROJECT/om ]; then
    echo "Making build project om subdir"
    mkdir build/classes/$PROJECT/om
  fi
  if [ ! -d build/classes/$PROJECT/map ]; then
    echo "Making build project map subdir"
    mkdir build/classes/$PROJECT/map
  fi
  if [ ! -d build/conf ]; then
    echo "Making build conf subdir"
    mkdir build/conf
  fi
  if [ ! -d build/sql ]; then
    echo "Making build sql subdir"
    mkdir build/sql
  fi
  
  cp build/classes/$PROJECT/*.php ../$PROJECT/ -R
  cp build/classes/$PROJECT/om/*.php ../$PROJECT/om/ -R
  cp build/classes/$PROJECT/map/*.php ../$PROJECT/map/ -R
  
  chown apache:apache ../$PROJECT -R
  echo "Removing Old Conf File"
  rm -rf ../conf/$PROJECT-conf.php
  echo "Copying New Conf File"
  cp build/conf/$PROJECT-conf.php ../conf/
  
fi

echo "Finished with no errors" > $SITEROOT/logs/wtvr_gen.log 2>&1

echo "DONE"
exit 0
