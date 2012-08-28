#!/usr/bin/perl

use FCGI;
#perl -MCPAN -e 'install FCGI'
use Socket;
use POSIX qw(setsid);
#use Fcntl;

require 'syscall.ph';

#this keeps the program alive or something after exec'ing perl scripts
END() { } BEGIN() { }
*CORE::GLOBAL::exit = sub { die "fakeexit\nrc=".shift()."\n"; }; 
eval q{exit}; 
if ($@) { 
	exit unless $@ =~ /^fakeexit/; 
};

&main;

sub main {
	chdir '/'                 or die "Can't chdir to /: $!";
	setsid                    or die "Can't start a new session: $!";
	umask 0;
	$socket = FCGI::OpenSocket( "/var/run/nginx/perl_cgi-dispatch.sock", 100 ); #use UNIX sockets - user running this script must have w access to the 'nginx' folder!!

	my $pid = 1;
	for ($i = 0; $i < 50 && $pid != 0; $i++) {
		$pid = fork;
    	}
	exit if $pid != 0;

        $request = FCGI::Request( \*STDIN, \*STDOUT, \*STDERR, \%req_params, $socket );
        if ($request) { request_loop()};
            FCGI::CloseSocket( $socket );
}

sub request_loop {
        while( $request->Accept() >= 0 ) {
            
           #processing any STDIN input from WebServer (for CGI-POST actions)
           $stdin_passthrough ='';
           $req_len = 0 + $req_params{'CONTENT_LENGTH'};
           if (($req_params{'REQUEST_METHOD'} eq 'POST') && ($req_len != 0) ){ 
                my $bytes_read = 0;
                while ($bytes_read < $req_len) {
                        my $data = '';
                        my $bytes = read(STDIN, $data, ($req_len - $bytes_read));
                        last if ($bytes == 0 || !defined($bytes));
                        $stdin_passthrough .= $data;
                        $bytes_read += $bytes;
                }
            }

            #running the cgi app
            if ( (-x $req_params{SCRIPT_FILENAME}) &&  #can I execute this?
                 (-s $req_params{SCRIPT_FILENAME}) &&  #Is this file empty?
                 (-r $req_params{SCRIPT_FILENAME})     #can I read this file?
            ){
		pipe(CHILD_RD, PARENT_WR);
		my $pid = open(KID_TO_READ, "-|");
		unless(defined($pid)) {
			print("Content-type: text/plain\r\n\r\n");
                        print "Error: CGI app returned no output - Executing $req_params{SCRIPT_FILENAME} failed !\n";
			next;
		}
		if ($pid > 0) {
			close(CHILD_RD);
			print PARENT_WR $stdin_passthrough;
			close(PARENT_WR);

			while(my $s = <KID_TO_READ>) { print $s; }
			close KID_TO_READ;
			waitpid($pid, 0);
		} else {
	                foreach $key ( keys %req_params){
        	           $ENV{$key} = $req_params{$key};
                	}
        	        # cd to the script's local directory
	                if ($req_params{SCRIPT_FILENAME} =~ /^(.*)\/[^\/]+$/) {
                        	chdir $1;
                	}

			close(PARENT_WR);
			close(STDIN);
			#fcntl(CHILD_RD, F_DUPFD, 0);
			syscall(&SYS_dup2, fileno(CHILD_RD), 0);
			#open(STDIN, "<&CHILD_RD");
			exec($req_params{SCRIPT_FILENAME});
			die("exec failed");
		}
            } 
            else {
                print("Content-type: text/plain\r\n\r\n");
                print "Error: No such CGI app - $req_params{SCRIPT_FILENAME} may not exist or is not executable by this process.\n";
            }

        }
}

