export GRINDERPATH=/usr/lib/grinder-371/
export PATH=$GRINDER_HOME:$PATH
export CLASSPATH=/usr/lib/grinder-371/lib/grinder.jar:$CLASSPATH

alias grinder="java net.grinder.Grinder $1"
alias grinderConsole="java net.grinder.Console"
alias grinderProxy="java net.grinder.TCPProxy -console -http > grinder.py"
