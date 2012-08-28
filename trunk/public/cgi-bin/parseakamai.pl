#!/usr/bin/perl

if ($#ARGV != 0 ) {
	print "usage: parseakamai.pl environment[dev|stage|prod]\n";
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

@files = <../log/*.gz>;
foreach $file (@files) {
   print "Opening " . $file . "\n";
   open(IN, "gunzip -c $file |") || die "can't open pipe to $file";
   
  my $i = 0;
  $ALOGFILE = $file;
  my @dest = split('/',$file);
  #print $dest[5];
  $ARCHIVE = "../log/archive/".$dest[5];
  open(LOGFILE, "gunzip -c $file |") or die("Could not open log file.");
  foreach $line (<LOGFILE>) {
      chomp($line);              # remove the newline from $line.
      # do line-by-line processing.
      ($date, $time, $csip, $csmethod, $csuri, $csstatus, $csbytes, $cstimetaken, $csreferer, $csuseragent, $cscookie ) = split(' ',$line);
      if (($csstatus ne '') && ($csstatus ne 0) && ($csip != 'time')) {
        doInsert( $dsn, \@dbh, "insert into log_akamailog (log_akamailog_date,log_akamailog_time,log_akamailog_ip,log_akamailog_method,log_akamailog_uri,log_akamailog_status,log_akamailog_bytes,log_akamailog_timetaken,log_akamailog_referer,log_akamailog_user_agent,log_akamailog_cookie) values ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )", ($date, $time, $csip, $csmethod, $csuri, $csstatus, $csbytes, $cstimetaken, $csreferer, $csuseragent, $cscookie ) );
        print "Processed line " . $i."\n";
        $i++;
      }
  }
  
  move($ALOGFILE, $ARCHIVE);
}
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
