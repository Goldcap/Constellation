#!/usr/bin/perl -I Perl -I ./
#
# This script runs a test on the AuthToken perl module
#
# $Id: //projects/core/TokenGenerators/akamai/Perl/gentoken.pl#2 $

use MakeToken;
use Getopt::Std;

use strict;

my(%option);

getopts ("f:i:r:p:x:k:w:t:s:T:d:h", \%option);

if ($option{h}) {
    print "Akamai Secure Streaming, Perl token generator version 3.0\n";
    print "usage: $ARGV[0] [options]\n";
    print "   -f path          path of file being requested\n";
    print "   -i ip            ip address of requester\n";
    print "   -r profile       profile\n";
    print "   -p passwd        site specific password\n";
    print "   -k key           key file name and path (for e type tokens in binary format, 32bytes long)\n";
    print "   -x payload       payload\n";
    print "   -w window        time window in seconds\n";
    print "   -t time          anchor point of window\n";
    print "   -s salt          pre-generated salt\n";
    print "   -T type          type of token ('a', 'c', 'd' or 'e')\n";
    print "   -d render_duration  rendering duration (valid only for C-, D-, and E-type tokens)\n";
    print "   -h               usage help\n";
    exit;
}

my($token_type) = $option{T} if defined $option{T};
my($path)       = $option{f} if defined $option{f};
my($ip)         = $option{i} if defined $option{i};
my($profile)    = $option{r} if defined $option{r};
my($passwd)     = $option{p} if defined $option{p};
my($payload)    = $option{x} if defined $option{x};
my($window)     = $option{w} if defined $option{w};
my($time  )     = $option{t} if defined $option{t};
my($salt)       = $option{s} if defined $option{s};
my($keyfile)    = $option{k} if defined $option{k};
my($renddur)    = $option{d} if defined $option{d};
my($key);
my($token);

#print "$keyfile\n";
if(defined $renddur && $renddur<0)
{
    print "Rendering duration should be greater than 0!\n";
    exit 0;
}
if($token_type ne "a" && $token_type ne "c" && $token_type ne "d" && $token_type ne "e")
{
    print "Invalid token type!\n";
    exit 0;
}
if($token_type eq "e")
{
    if(!$keyfile)
    {
	print "You have to provide encryption key for e type tokens\n";
	exit 0;
    }

    open KEY, $keyfile or die "Cannot open $keyfile for read :$!";
    if (read(KEY, $key, 32)!=32)
    {
	print "Failed to read from $key.\n";
	exit 0;
    }
 }

if($token_type ne "e" && $keyfile)
{
    print "Only e type tokens need the key file!\n";
    exit 0
}

if ($token_type && ($token_type eq "a")) {
    $token = &MakeToken(
			type=>$token_type,
			path=>$path,
			ip=>$ip,
			profile=>$profile,
			passwd=>$passwd,
			window=>$window,
			time=>$time,
			payload=>$payload,
			salt=>$salt,
			);
} else {
    # Default to C,D,E-type tokens
    $token = &MakeCDEtypeToken(
			       type=>$token_type,
			       path=>$path,
			       ip=>$ip,
			       profile=>$profile,
			       passwd=>$passwd,
			       window=>$window,
			       time=>$time,
			       payload=>$payload,
			       key=>$key,
                   renddur=>$renddur
			     );
}

print "$token\n";

exit 0;
