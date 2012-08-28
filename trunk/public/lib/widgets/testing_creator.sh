#!/bin/bash

for widget in `ls`
do
  echo "Testing for testVars.ytml in "$widget
  if [ ! -f "$widget/conf/testVars.yml" ]; then
    cp testVars.yml $widget/conf
  fi
  if [ ! -f "$widget/$widget.test.php" ]; then
    cp Widget.test.php $widget/$widget.test.php.tmp
    sed -e "s/#widget#/$widget/g" $widget/$widget.test.php.tmp > $widget/$widget.test.php
    rm -rf $widget/$widget.test.php.tmp
  else
    sed -e "s/\$widget/$widget/g" $widget/$widget.test.php > $widget/$widget.test.php.tmp
    rm -rf $widget/$widget.test.php
    mv $widget/$widget.test.php.tmp $widget/$widget.test.php
  fi
done
