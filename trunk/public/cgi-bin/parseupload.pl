#!/usr/bin/perl

if ($#ARGV != 0 ) {
	print "usage: parselog.pl environment[dev|stage|prod]\n";
	exit;
}
my $environment=$ARGV[0];

use URI::Escape;
use CGI;
use DBD::mysql;
use Net::SMTP;
use File::Copy;
use POSIX qw/strftime/;

require "db.pl";

my $cdn;
my $host;
my $dsn;
my $dbh;
my $sending=false;

if ($environment eq "prod") {
  #=========================================
  # LIVE SERVER
  #=========================================
  $host = "www";
  $cdn = "$cdn";
  $dsn = "DBI:mysql:database=constellation;host=db.constellation.tv;";
  @dbh = ("root", "constellation2010");
}

if ($environment eq "test") {
  #=========================================
  # LIVE SERVER
  #=========================================
  $host = "test";
  $cdn = "$cdn";
  $dsn = "DBI:mysql:database=constellation_test;host=test.db.constellation.tv;";
  @dbh = ("root", "constellation2010");
}

if ($environment eq "stage") {
  #=========================================
  # STAGE SERVER
  #=========================================
  $host = "stage";
  $cdn = "$cdn";
  $dsn = "DBI:mysql:database=constellation_stage;host=localhost;";
  @dbh = ("root", "constellation2010");
}

if ($environment eq "dev") {
  #=========================================
  # DEV SERVER
  #=========================================
  $host = "dev";
  $cdn = "$cdn";
  $dsn = "DBI:mysql:database=constellation_dev;host=192.168.2.7;";
  @dbh = ("root", "1hsvy5qb");
}

sub in_array
{
   my ($arr,$search_for) = @_;
   my %items = map {$_ => 1} @$arr; # create a hash out of the array values
   return (exists($items{$search_for}))?1:0;
}
my $ticket;
my $message;
my $date;
my $port;
my $err;
my $text;
my $i = 0;
$LOGFILE = "/var/log/vsftpd.log"; 
$ARCHIVE = "/var/log/archive/".time()."_vsftpd.log";
open(LOGFILE) or die("Could not open log file.");
foreach $line (<LOGFILE>) {
    chomp($line);              # remove the newline from $line.
    # do line-by-line processing.
    if ($line =~ /\.mov/) {
	  print $line."\n";
    $line =~ /([^\[]+) \[([^\]]+)\] \[([^\]]+)\] OK UPLOAD: Client "([^"]+)", "\/(\d+)\/([^"]+)", (\d+)([^,]+)(.+)/;
    print "Film " . $film."\n"; 
    $date = $1;
    $user = $3;
    $client = $4;
    $film = $5; 
    $file = $6;
    $size = $7;
    $now = strftime('%Y-%m-%d %H:%M',localtime);
    if (($film ne "") and ($file ne "") and ($file =~ /\.mov/)) { 
    print "Date " . $date."\n";
    print "User " . $user."\n"; 
    print "Client " . $client."\n"; 
    print "Film " . $film."\n"; 
    print "File " . $file."\n";
    print "Size " . $size."\n";
	    doInsert( $dsn, \@dbh, "insert into file_upload (file_upload_user,file_upload_filename,file_upload_date_discovery,file_upload_date,file_upload_status,file_upload_film,file_upload_client,file_upload_size) values ( ?, ?, ?, ?, ?, ?, ?, ? )", ( $user, $file, $now, $date, 1, $film, $client, $size ) );
    }}
    #print "Processed line " . $i."\n";
    $i++;
}

#move($LOGFILE, $ARCHIVE);

#open FILE, ">/var/log/vsftpd.log" or die $!;
#print FILE "";
#close FILE;

my $doemail = 0;
my $query = new CGI;
my $date =localtime();
my $time = time();

if ($doemail == 1) {
$smtp = Net::SMTP->new('localhost');

$smtp->mail('amadsen@constellation.tv');
$smtp->to('amadsen@constellation.tv');

$smtp->data();
$smtp->datasend("To: Andy Madsen <amadsen>\n");
$smtp->datasend("Subject: Logs Parsed\n");
$smtp->datasend("\n");

my $thedata = "Youse logs were friggin parsed on ".$date."\n\n";

$smtp->datasend($thedata);
$smtp->dataend();

$smtp->quit;
}

print "DONE\n";
exit 0; 
