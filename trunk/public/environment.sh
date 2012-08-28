#!/bin/bash

YESTERDAY=`date --date='1 days ago' +%m,%d,%Y`

getEnvironment() {
  
  # And here we have Bash Patterns:
  if [[ "$SCRIPT_PATH" == *dev.constellation* ]]
  then
      ENVIRONMENT=dev
      return 0
  fi
  if [[ "$SCRIPT_PATH" == *stage.constellation* ]]
  then
      ENVIRONMENT=stage
      return 0
  fi
  if [[ "$SCRIPT_PATH" == *www.constellation* ]]
  then
      ENVIRONMENT=prod
      return 0
  fi
  if [[ "$SCRIPT_PATH" == none ]]
  then
      ENVIRONMENT=none
      echo "ERROR! No Environment Found"
      return 0
  fi
}
