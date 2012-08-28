@REM ----------------------------------------------------------------------------
@REM Copyright 2001-2004 The Apache Software Foundation.
@REM
@REM Licensed under the Apache License, Version 2.0 (the "License");
@REM you may not use this file except in compliance with the License.
@REM You may obtain a copy of the License at
@REM
@REM      http://www.apache.org/licenses/LICENSE-2.0
@REM
@REM Unless required by applicable law or agreed to in writing, software
@REM distributed under the License is distributed on an "AS IS" BASIS,
@REM WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
@REM See the License for the specific language governing permissions and
@REM limitations under the License.
@REM ----------------------------------------------------------------------------
@REM

@echo off

set ERROR_CODE=0

:init
@REM Decide how to startup depending on the version of windows

@REM -- Win98ME
if NOT "%OS%"=="Windows_NT" goto Win9xArg

@REM set local scope for the variables with windows NT shell
if "%OS%"=="Windows_NT" @setlocal

@REM -- 4NT shell
if "%eval[2+2]" == "4" goto 4NTArgs

@REM -- Regular WinNT shell
set CMD_LINE_ARGS=%*
goto WinNTGetScriptDir

@REM The 4NT Shell from jp software
:4NTArgs
set CMD_LINE_ARGS=%$
goto WinNTGetScriptDir

:Win9xArg
@REM Slurp the command line arguments.  This loop allows for an unlimited number
@REM of arguments (up to the command line limit, anyway).
set CMD_LINE_ARGS=
:Win9xApp
if %1a==a goto Win9xGetScriptDir
set CMD_LINE_ARGS=%CMD_LINE_ARGS% %1
shift
goto Win9xApp

:Win9xGetScriptDir
set SAVEDIR=%CD%
%0\
cd %0\..\.. 
set BASEDIR=%CD%
cd %SAVEDIR%
set SAVE_DIR=
goto repoSetup

:WinNTGetScriptDir
set BASEDIR=%~dp0\..

:repoSetup


if "%JAVACMD%"=="" set JAVACMD=java

if "%REPO%"=="" set REPO=%BASEDIR%\lib

set CLASSPATH="%BASEDIR%"\etc;"%REPO%"\agent-3.1.10.jar;"%REPO%"\json-20090211.jar;"%REPO%"\browsermob-centcom-api-3.1.10.jar;"%REPO%"\collector-api-3.1.10.jar;"%REPO%"\config-3.1.10.jar;"%REPO%"\guice-1.0.jar;"%REPO%"\agent-api-3.1.10.jar;"%REPO%"\monitoring-api-3.1.10.jar;"%REPO%"\runner-api-3.1.10.jar;"%REPO%"\jackson-core-asl-1.6.4.jar;"%REPO%"\jets3t-0.7.3.jar;"%REPO%"\commons-httpclient-3.1.jar;"%REPO%"\typica-1.7.2.jar;"%REPO%"\httpclient-4.1.1.jar;"%REPO%"\httpcore-4.1.jar;"%REPO%"\httpmime-4.0.jar;"%REPO%"\apache-mime4j-0.6.jar;"%REPO%"\htmlunit-2.4.jar;"%REPO%"\xalan-2.7.1.jar;"%REPO%"\serializer-2.7.1.jar;"%REPO%"\xml-apis-1.3.04.jar;"%REPO%"\commons-collections-3.2.1.jar;"%REPO%"\commons-lang-2.1.jar;"%REPO%"\htmlunit-core-js-2.4.jar;"%REPO%"\nekohtml-1.9.11.jar;"%REPO%"\xercesImpl-2.8.1.jar;"%REPO%"\cssparser-0.9.5.jar;"%REPO%"\sac-1.3.jar;"%REPO%"\hessian-3.2.0.jar;"%REPO%"\selenium-remote-control-2.0b3.jar;"%REPO%"\selenium-chrome-driver-2.0b3.jar;"%REPO%"\selenium-htmlunit-driver-2.0b3.jar;"%REPO%"\selenium-firefox-driver-2.0b3.jar;"%REPO%"\selenium-ie-driver-2.0b3.jar;"%REPO%"\jna-3.2.2.jar;"%REPO%"\selenium-iphone-driver-2.0b3.jar;"%REPO%"\selenium-server-2.0b3.jar;"%REPO%"\bcprov-jdk15-135.jar;"%REPO%"\mx4j-tools-3.0.1.jar;"%REPO%"\servlet-api-2.5-6.1.9.jar;"%REPO%"\selenium-support-2.0b3.jar;"%REPO%"\selenium-remote-driver-2.0b3.jar;"%REPO%"\cglib-nodep-2.1_3.jar;"%REPO%"\selenium-api-2.0b3.jar;"%REPO%"\guava-r07.jar;"%REPO%"\commons-codec-1.3.jar;"%REPO%"\ant-1.7.1.jar;"%REPO%"\ant-launcher-1.7.1.jar;"%REPO%"\bcprov-jdk16-1.43.jar;"%REPO%"\servlet-api-2.4.jar;"%REPO%"\google-collections-1.0-rc3.jar;"%REPO%"\jcifs-1.3.3.jar;"%REPO%"\mail-1.4.jar;"%REPO%"\activation-1.1.jar;"%REPO%"\aws-java-sdk-1.1.9.jar;"%REPO%"\stax-api-1.0.1.jar;"%REPO%"\guice-2.0.jar;"%REPO%"\aopalliance-1.0.jar;"%REPO%"\core-3.1.10.jar;"%REPO%"\loggly-1.0-beta-2.jar;"%REPO%"\jackson-mapper-asl-1.5.0.jar;"%REPO%"\browsermob-vnc-1.0-beta-1.jar;"%REPO%"\commons-cli-1.2.jar;"%REPO%"\amqp-client-2.2.0.jar;"%REPO%"\commons-io-1.2.jar;"%REPO%"\slf4j-jdk14-1.5.3.jar;"%REPO%"\slf4j-api-1.5.3.jar;"%REPO%"\commons-logging-1.1.1.jar;"%REPO%"\local-validator-3.1.10.jar
set EXTRA_JVM_ARGUMENTS=
goto endInit

@REM Reaching here means variables are defined and arguments have been captured
:endInit

%JAVACMD% %JAVA_OPTS% %EXTRA_JVM_ARGUMENTS% -classpath %CLASSPATH_PREFIX%;%CLASSPATH% -Dapp.name="validator" -Dapp.repo="%REPO%" -Dbasedir="%BASEDIR%" com.browsermob.local.Main %CMD_LINE_ARGS%
if ERRORLEVEL 1 goto error
goto end

:error
if "%OS%"=="Windows_NT" @endlocal
set ERROR_CODE=1

:end
@REM set local scope for the variables with windows NT shell
if "%OS%"=="Windows_NT" goto endNT

@REM For old DOS remove the set variables from ENV - we assume they were not set
@REM before we started - at least we don't leave any baggage around
set CMD_LINE_ARGS=
goto postExec

:endNT
@endlocal

:postExec

if "%FORCE_EXIT_ON_ERROR%" == "on" (
  if %ERROR_CODE% NEQ 0 exit %ERROR_CODE%
)

exit /B %ERROR_CODE%
