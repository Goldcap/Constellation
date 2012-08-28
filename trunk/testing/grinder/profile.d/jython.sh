
export JYTHON_HOME=/usr/lib/jython-22/
export PATH=$JYTHON_HOME:$PATH
export CLASSPATH=/usr/lib/jython-22/jython.jar:$CLASSPATH

alias jython="java -jar /usr/lib/jython-22/jython.jar $1"
