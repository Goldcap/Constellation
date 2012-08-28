#!/usr/bin/perl

if (%ENV->{'HTTP_X_FORWARDED_FOR'} eq "69.16.180.38") {
  exit;
}

use URI::Escape;
use CGI;
use DBD::mysql;
use Net::SMTP;

my $environment;

if (%ENV->{'SERVER_NAME'} eq "dev.constellation.tv") {
  $environment = "dev";
} elsif (%ENV->{'SERVER_NAME'} eq "stage.constellation.tv") {
  $environment = "stage";
} else {
  $environment = "prod";
}

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
  $dsn = "DBI:mysql:database=constellation;host=localhost;";
  #$dbh = DBI->connect($dsn, "amadsen", "1hsvy5qb");
}

if ($environment eq "stage") {
  #=========================================
  # STAGE SERVER
  #=========================================
  $host = "stage";
  $cdn = "$cdn";
  $dsn = "DBI:mysql:database=constellation_stage;host=localhost;";
  #$dbh = DBI->connect($dsn, "amadsen", "1hsvy5qb");
}

if ($environment eq "dev") {
  #=========================================
  # DEV SERVER
  #=========================================
  $host = "dev";
  $cdn = "$cdn";
  $dsn = "DBI:mysql:database=constellation_dev;host=localhost;";
  #$dbh = DBI->connect($dsn, "amadsen", "1hsvy5qb");
}

sub in_array
{
   my ($arr,$search_for) = @_;
   my %items = map {$_ => 1} @$arr; # create a hash out of the array values
   return (exists($items{$search_for}))?1:0;
}

my $doemail = 0;
my $query = new CGI;
my $date =localtime();
my $time = time();

my @bots = ('bingbot','Googlebot','YandexBot','msnbot','CrystalSemanticsBot','NextGenSearchBot'); # create a hash out of the array values
foreach (@bots) {
 	if (%ENV -> {'HTTP_USER_AGENT'} =~ /$_/) {
   $doemail = 0;
   break;
  }
}

#Figure out error
if ($query->param('c') ne "") {
  $errcode = $query->param('c');
} else {
  $errcode = "UNKNOWN";
}

if ($doemail == 1) {
$smtp = Net::SMTP->new('localhost');

$smtp->mail('amadsen@constellation.tv');
$smtp->to('amadsen@constellation.tv');

$smtp->data();
$smtp->datasend("To: Andy Madsen <amadsen>\n");
$smtp->datasend("Subject: ERROR ".$errcode." ON SERVER ".%ENV->{'HOSTNAME'}."\n");
$smtp->datasend("\n");

my $thedata = "Error ".$errcode." on ".$date." from \n\n".%ENV -> {'REQUEST_URI'}."\n\n";

foreach (sort keys %ENV) { 
  $thedata .= "$_  =  $ENV{$_}\n"; 
}

$smtp->datasend($thedata);
$smtp->dataend();

$smtp->quit;
}

print "Content-type: text/html\n\n";

my $out= <<ENDHTML;
<!DOCCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<meta name="title" content="Constellation - Your Online Movie Theater" /> 
	<title>Constellation - Your Online Movie Theater</title> 
	<link rel="stylesheet" type="text/css" media="screen" href="/css/main.css" /> 
  <link rel="stylesheet" type="text/css" media="screen" href="/css/skin.css" /> 
	<link rel="shortcut icon" href="/favicon.ico" />  

	</head>

	<body>

		<div id="first-splash">

			<a href="/" style="position: absolute; z-index: 100; top: 420px; left: 150px; color: red;">Sorry, there was an error!</a>

		</div>

	</body>

</html>
ENDHTML

print $out;

exit 0 
