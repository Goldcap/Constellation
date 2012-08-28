#!/usr/bin/perl

if ($#ARGV != 1 ) {
	print "usage: parselog.pl environment[dev|stage|prod] type[flash|wtvr]\n";
	exit;
}
my $environment=$ARGV[0];
my $type=$ARGV[1];

use URI::Escape;
use CGI;
use DBD::mysql;
use Net::SMTP;
use File::Copy;

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
  $dsn = "DBI:mysql:database=constellation_dev;host=localhost;";
  @dbh = ("amadsen", "1hsvy5qb");
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
$LOGFILE = "../log/".$type."log.log"; 
$ARCHIVE = "../log/archive/".time()."_".$type."log.log";
open(LOGFILE) or die("Could not open log file.");
foreach $line (<LOGFILE>) {
    chomp($line);              # remove the newline from $line.
    # do line-by-line processing.
    if ($type eq 'flash') {
      $line =~ m/^TICKET:: ([^\|]*) \| MESSAGE:: ([^\|]*) \| DATE:: ([^\|]*) \| PORT:: ([^\|]*)/;
      $ticket = $1;
      $message = $2;
      $date = $3;
      $port = $4;
      if ($2 =~ /Fatal/i) {
        $err = 3;
      } elsif ($2 =~ /Error/i) {
        $err = 2;
      } elsif ($2 =~ /Warn/i) {
        $err = 1;
      } elsif ($2 =~ /Info/i) {
        $err = 0;
      } else {
        $err = -1;
      }
      doInsert( $dsn, \@dbh, "insert into log_flashlog (flashlog_ticket,flashlog_message,flashlog_date,flashlog_port,flashlog_error) values ( ?, ?, ?, ?, ? )", ( $ticket, $message, $date, $port, $err ) );
    } else {
      $line =~ m/^USER:: ([^\|]*)\| MESSAGE:: ([^\|]*)\| DATE:: ([^\|]*)\| SERVER:: ([^\|]*)/;
      $user = $1;
      $message = $2;
      $date = $3;
      $server = $4;
      doInsert( $dsn, \@dbh, "insert into log_wtvrlog (fk_user_id,wtvrlog_message,wtvrlog_date,wtvrlog_server) values ( ?, ?, ?, ? )", ( $user, $message, $date, $server ) );
    }
    print "Processed line " . $i."\n";
    $i++;
}

move($LOGFILE, $ARCHIVE);

open FILE, ">../log/".$type."log.log" or die $!;
print FILE "";
close FILE;

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
