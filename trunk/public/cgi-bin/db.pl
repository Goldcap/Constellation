#!/usr/bin/perl

sub in_array
{
   my ($arr,$search_for) = @_;
   my %items = map {$_ => 1} @$arr; # create a hash out of the array values
   return (exists($items{$search_for}))?1:0;
}

sub throw {
  my $mess = join('', @_);
  $mess =~ s/\n?$/\n/;
  my $i = 1;
  local $" = "', '";
  package DB;
  while (my @parts = caller($i++)) {
  my $q; $q = "'" if @DB::args;
  $mess .= " -> $parts[3](@DB::args)" .
  " at $parts[1] line $parts[2]\n";
  }
  die $mess;
}

sub ucwords
{
 $str = shift;
 $str = lc($str);
 $str =~ s/%20/ /g;
 $str =~ s/\b(\w)/\u$1/g;
 $str =~ s/ /%20/g;
 return $str;
}

sub doQuery
{
  my ( $dq_dsn, $dq_dbh, $dq_psql, @dq_opts ) = @_;
  my $dq_dbh = DBI->connect($dq_dsn, "amadsen", "1hsvy5qb");
  
  $dq_sth= $dq_dbh -> prepare($dq_psql);
  if (@dq_opts) {
    my $i=1;
    foreach $dq_arg (@dq_opts) {
      $dq_sth->bind_param($i, $dq_arg);
      $i++;
    }
  }
  $dq_sth->execute;
  
  my @dq_matrix = ();
  while (@dq_ary = $dq_sth->fetchrow_array())
  {
      push(@dq_matrix, [@dq_ary]);  # [@ary] is a reference
  }
  $dq_sth->finish();
  $dq_dbh->disconnect();
  
  return @dq_matrix;
}

sub doInsert
{
  my ( $di_dsn, $di_dbh, $di_psql, @di_opts ) = @_;
  my $di_dbh = DBI->connect($di_dsn, @$di_dbh[0], @$di_dbh[1]);
  $di_sth= $di_dbh -> prepare($di_psql);
  if (@di_opts) {
    my $i=1;
    foreach $di_arg (@di_opts) {
      $di_sth->bind_param($i, $di_arg);
      $i++;
    }
  }
  $di_sth->execute;
  
  $di_sth->finish();
  $di_dbh->disconnect();
  
  return true;
}

sub getPage
{
  my $query = new CGI;
  
  #Figure out offset
  if ($query->param('page') ne "") {
    if ($query->param('page') =~ /^[+-]?\d+$/) {
      $page = $query->param('page');
    }
  } else {
    $page = 1;
  }
  return $page;
}

sub getParam
{
  my $gp_query = @_[0];
  my $gp_name = @_[1];
  my $gp_default = @_[2];
  my $gp_filter = @_[3];
  my $result=false;
  #Figure out offset
  if ($gp_query->param($gp_name) ne "") {
    if ($gp_query->param($gp_name) =~ /$gp_filter/) {
      $result = $gp_query->param($gp_name);
    }
  } else {
    $result = $gp_default;
  }
  return $result;
}

sub doCount
{
  my $dc_dsn = @_[0];
  my $dc_dbh = @_[1]; 
  my $dbh = DBI->connect($dc_dsn, "amadsen", "1hsvy5qb");
  my $psql = @_[1];
  
  $sth= $dbh -> prepare($psql);
  if (@_[2]) {
    my $i=1;
    foreach $arg (@_[2]) {
      $sth->bind_param($i, $arg);
      $i++;
    }
  } 
  $sth->execute;
  while ($item = $sth->fetchrow_array())
  {
      $size = $item;
  }
  $sth->finish();
  $dbh->disconnect();
  
  return $size;
}

sub doXml
{
  print "Content-type: text/xml\n\n";
  print @_[0]->toString();
}


sub kickdump
{
  print @_[0];
}

sub dodump
{
  print "Content-type: text/html\n\n";
  print @_[0];
  exit 0;
}

sub doHtml
{
  print "Content-type: text/html\n\n";
  print @_[0];
}

sub doCss
{
  print "Content-type: text/css\n\n";
  print @_[0];
}

1;
